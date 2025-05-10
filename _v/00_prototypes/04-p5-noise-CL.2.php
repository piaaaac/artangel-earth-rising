<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>2D Noise with Dithering</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.6.0/p5.min.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      overflow: hidden;
    }

    canvas {
      display: block;
    }

    #info {
      background: linear-gradient(to right, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.0));
      position: fixed;
      color: white;
      top: 0;
      left: 0;
      padding: 10px;
      font-family: "Menlo", 'Courier New', Courier, monospace;
      font-size: 9px;
    }
  </style>
</head>

<body>
  <div id="info">
    FPS: 12
  </div>

  <script>
    let noiseScale = 0.001;
    let timeSpeed = 0.001;
    let zOffset = 0;
    let ditherPattern = 'floydSteinberg'; // Options: 'bayer', 'blueNoise', 'ordered', 'floydSteinberg'
    let colorMode = 'mono';
    let ditherThreshold = 0.05;
    let bayerMatrix;
    let blueNoiseTexture;
    let controlsVisible = true;

    function setup() {
      createCanvas(windowWidth, windowHeight);
      pixelDensity(1); // Ensure consistent pixel density
      createBayerMatrix();
      createBlueNoise();
    }

    function createBayerMatrix() {
      // Create 8x8 Bayer dithering matrix
      bayerMatrix = [
        [0, 32, 8, 40, 2, 34, 10, 42],
        [48, 16, 56, 24, 50, 18, 58, 26],
        [12, 44, 4, 36, 14, 46, 6, 38],
        [60, 28, 52, 20, 62, 30, 54, 22],
        [3, 35, 11, 43, 1, 33, 9, 41],
        [51, 19, 59, 27, 49, 17, 57, 25],
        [15, 47, 7, 39, 13, 45, 5, 37],
        [63, 31, 55, 23, 61, 29, 53, 21]
      ];

      // Normalize the matrix values to range 0-1
      for (let y = 0; y < 8; y++) {
        for (let x = 0; x < 8; x++) {
          bayerMatrix[y][x] = bayerMatrix[y][x] / 64;
        }
      }
    }

    function createBlueNoise() {
      // Create a simple blue noise texture for dithering
      blueNoiseTexture = createGraphics(64, 64);
      blueNoiseTexture.loadPixels();

      // Using a simple algorithm to approximate blue noise
      for (let y = 0; y < blueNoiseTexture.height; y++) {
        for (let x = 0; x < blueNoiseTexture.width; x++) {
          let v = noise(x * 0.15, y * 0.15, 999) * 0.5 +
            noise(x * 0.3, y * 0.3, 888) * 0.3 +
            noise(x * 0.6, y * 0.6, 777) * 0.2;

          let idx = (y * blueNoiseTexture.width + x) * 4;
          blueNoiseTexture.pixels[idx] = v * 255;
          blueNoiseTexture.pixels[idx + 1] = v * 255;
          blueNoiseTexture.pixels[idx + 2] = v * 255;
          blueNoiseTexture.pixels[idx + 3] = 255;
        }
      }

      blueNoiseTexture.updatePixels();
    }

    function getBayerValue(x, y) {
      return bayerMatrix[y % 8][x % 8];
    }

    function logInfo(data) {
      const infoDiv = document.getElementById('info');
      infoDiv.innerHTML = `FPS: ${Math.round(frameRate())}<br>${data}`;
    }

    function getBlueNoiseValue(x, y) {
      // Get blue noise value from the texture
      const index = ((y % blueNoiseTexture.height) * blueNoiseTexture.width + (x % blueNoiseTexture.width)) * 4;
      return blueNoiseTexture.pixels[index] / 255;
    }

    function getOrderedDitherValue(x, y) {
      // Simple ordered pattern
      const pattern = [
        [0.0, 0.5],
        [0.75, 0.25]
      ];
      return pattern[y % 2][x % 2];
    }

    function applyFloydSteinberg(buffer, width, height) {
      // Simplified Floyd-Steinberg dithering
      let result = new Uint8Array(buffer.length);

      // Copy the original pixel data
      for (let i = 0; i < buffer.length; i++) {
        result[i] = buffer[i];
      }

      // Apply error diffusion
      for (let y = 0; y < height; y++) {
        for (let x = 0; x < width; x++) {
          const idx = (y * width + x) * 4;

          // Get current pixel value
          const oldPixel = result[idx] / 255;

          // Determine new pixel value (black or white)
          const newPixel = oldPixel < ditherThreshold ? 0 : 1;

          // Calculate quantization error
          const error = oldPixel - newPixel;

          // Set the pixel
          result[idx] = result[idx + 1] = result[idx + 2] = newPixel * 255;

          // Distribute error to neighboring pixels
          if (x < width - 1) {
            result[(y * width + x + 1) * 4] += error * 255 * 7 / 16;
          }

          if (y < height - 1) {
            if (x > 0) {
              result[((y + 1) * width + x - 1) * 4] += error * 255 * 3 / 16;
            }

            result[((y + 1) * width + x) * 4] += error * 255 * 5 / 16;

            if (x < width - 1) {
              result[((y + 1) * width + x + 1) * 4] += error * 255 * 1 / 16;
            }
          }
        }
      }

      return result;
    }

    function draw() {
      background(0);
      loadPixels();

      let t = new TimerAP();

      // Prepare a buffer for Floyd-Steinberg dithering
      let buffer = new Uint8Array(pixels.length);

      zOffset += timeSpeed;

      t.recordStep("line 183");
      for (let y = 0; y < height; y++) {
        t.recordStep("each y");
        for (let x = 0; x < width; x++) {
          // Calculate noise value
          let noiseVal = noise(x * noiseScale, y * noiseScale, zOffset);

          // Apply dithering based on selected pattern
          let dither = 0;
          if (ditherPattern === 'bayer') {
            dither = getBayerValue(x, y);
          } else if (ditherPattern === 'blueNoise') {
            dither = getBlueNoiseValue(x, y);
          } else if (ditherPattern === 'ordered') {
            dither = getOrderedDitherValue(x, y);
          }

          // Calculate color based on color mode
          let r, g, b;

          if (colorMode === 'mono') {
            r = g = b = noiseVal * 255;
          } else if (colorMode === 'gradient') {
            r = map(noiseVal, 0, 1, 0, 255);
            g = map(noiseVal, 0, 1, 100, 200);
            b = map(noiseVal, 0, 1, 255, 0);
          } else if (colorMode === 'rainbow') {
            // Rainbow coloring
            let h = (noiseVal * 360) % 360;
            let s = 1;
            let v = 1;

            // HSV to RGB conversion
            let c = v * s;
            let x = c * (1 - Math.abs(((h / 60) % 2) - 1));
            let m = v - c;

            if (h < 60) {
              r = c;
              g = x;
              b = 0;
            } else if (h < 120) {
              r = x;
              g = c;
              b = 0;
            } else if (h < 180) {
              r = 0;
              g = c;
              b = x;
            } else if (h < 240) {
              r = 0;
              g = x;
              b = c;
            } else if (h < 300) {
              r = x;
              g = 0;
              b = c;
            } else {
              r = c;
              g = 0;
              b = x;
            }

            r = (r + m) * 255;
            g = (g + m) * 255;
            b = (b + m) * 255;
          }

          // Apply dithering for non-Floyd-Steinberg patterns
          if (ditherPattern !== 'floydSteinberg') {
            // Apply threshold with dither pattern
            let thresholdedVal = (noiseVal < ditherThreshold - 0.5 + dither) ? 0 : 255;

            if (colorMode === 'mono') {
              r = g = b = thresholdedVal;
            } else {
              // For colored modes, just apply the dithering as an overlay
              r = map(noiseVal < ditherThreshold - 0.5 + dither ? 0 : 1, 0, 1, 0, r);
              g = map(noiseVal < ditherThreshold - 0.5 + dither ? 0 : 1, 0, 1, 0, g);
              b = map(noiseVal < ditherThreshold - 0.5 + dither ? 0 : 1, 0, 1, 0, b);
            }
          }

          // Calculate pixel index
          let idx = (y * width + x) * 4;

          // Store in buffer or pixels
          if (ditherPattern === 'floydSteinberg') {
            buffer[idx] = r;
            buffer[idx + 1] = g;
            buffer[idx + 2] = b;
            buffer[idx + 3] = 255;
          } else {
            pixels[idx] = r;
            pixels[idx + 1] = g;
            pixels[idx + 2] = b;
            pixels[idx + 3] = 255;
          }
        }
      }
      t.recordStep("line 282");

      // Apply Floyd-Steinberg dithering if selected
      if (ditherPattern === 'floydSteinberg') {
        let result = applyFloydSteinberg(buffer, width, height);
        for (let i = 0; i < pixels.length; i++) {
          pixels[i] = result[i];
        }
      }

      updatePixels();

      logInfo();
      t.log();
    }

    function windowResized() {
      resizeCanvas(windowWidth, windowHeight);
    }

    class TimerAP {
      constructor() {
        this.startTime = millis();
        this.lastStep = this.startTime;
        this.steps = [];
      }

      recordStep(name = '') {
        let currentTime = Math.round(millis());
        let stepLength = currentTime - this.lastStep;
        this.lastStep = currentTime;
        this.steps.push({
          "name": name,
          "l": stepLength
        });
      }

      log() {
        let barLengthMax = 10;
        let longestStep = this.steps.reduce((max, step) => Math.max(max, step.l), 0);
        this.steps.forEach((step, index) => {
          let barLength = Math.round(map(step.l, 0, longestStep, 0, barLengthMax));
          let basStr = 'x'.repeat(barLength) + '-'.repeat(barLengthMax - barLength);
          console.log(`(${basStr}) ${index + 1} ${step.name}: ${step.l} ms`);
        });
      }
    }
  </script>
</body>

</html>