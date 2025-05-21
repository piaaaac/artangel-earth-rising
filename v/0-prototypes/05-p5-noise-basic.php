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

    function setup() {
      createCanvas(windowWidth, windowHeight);
      pixelDensity(1); // Ensure consistent pixel density
    }

    function logInfo(data) {
      const infoDiv = document.getElementById('info');
      infoDiv.innerHTML = `FPS: ${Math.round(frameRate())}<br>${data??""}`;
    }


    function draw() {
      background(0);

      // ------------------------------
      // IPER VERY SUPER SLOW
      // let noiseLevel = 255;
      // let noiseScale = 0.009;
      // for (let y = 0; y < height; y += 1) {
      //   for (let x = 0; x < width; x += 1) {
      //     let nx = noiseScale * x;
      //     let ny = noiseScale * y;
      //     let nt = noiseScale * frameCount;
      //     let c = noiseLevel * noise(nx, ny, nt);
      //     stroke(c);
      //     point(x, y);
      //   }
      // }


      // ------------------------------
      // SLOW
      loadPixels();
      zOffset += timeSpeed;
      for (let y = 0; y < height; y++) {
        for (let x = 0; x < width; x++) {
          let index = (x + y * width) * 4;
          let noiseVal = noise(x * noiseScale, y * noiseScale, zOffset);
          let brightness = map(noiseVal, 0, 1, 0, 255);
          pixels[index] = brightness; // R
          pixels[index + 1] = brightness; // G
          pixels[index + 2] = brightness; // B
          pixels[index + 3] = 255; // A
        }
      }
      updatePixels();
      // ------------------------------

      logInfo();
    }

    function windowResized() {
      resizeCanvas(windowWidth, windowHeight);
    }
  </script>
</body>

</html>