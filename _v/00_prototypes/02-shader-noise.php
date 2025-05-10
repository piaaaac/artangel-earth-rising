<!-- boilerplate via chat gpt -->

<?php
// index.php - single file PHP web page with p5.js shader sketch
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>p5.js Shader Noise</title>
  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      overflow: hidden;
    }

    canvas {
      display: block;
    }
  </style>
  <!-- p5.js and p5.js WebGL libraries -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.0/p5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.0/addons/p5.dom.min.js"></script>
</head>

<body>

  <script id="vertex-shader" type="x-shader/x-vertex">
    attribute vec3 aPosition;
attribute vec2 aTexCoord;
varying vec2 vTexCoord;

void main () {
  // copy the coordinates
  vTexCoord = aTexCoord;

  vec4 posVec4 = vec4(aPosition, 1.0);
  //posVec4.xy = posVec4.xy * 2.0 - 1.0;
  gl_Position = posVec4;
}
</script>


  <script id="fragment-shader" type="x-shader/x-fragment">
    precision mediump float;

uniform vec2 resolution;
uniform float time;

// 2D pseudo-random gradient noise
float hash(vec2 p) {
  return fract(sin(dot(p, vec2(127.1, 311.7))) * 43758.5453);
}

float noise(vec2 p) {
  vec2 i = floor(p);
  vec2 f = fract(p);
  float a = hash(i);
  float b = hash(i + vec2(1.0, 0.0));
  float c = hash(i + vec2(0.0, 1.0));
  float d = hash(i + vec2(1.0, 1.0));
  vec2 u = f*f*(3.0-2.0*f);
  return mix(a, b, u.x) + (c - a)*u.y*(1.0 - u.x) + (d - b)*u.x*u.y;
}

void main() {
  vec2 uv = gl_FragCoord.xy / resolution.xy;
  uv *= 4.0;
  float n = noise(uv + time * 0.1);
  gl_FragColor = vec4(n, 0.0, 0.0, 1.0);
}
</script>

  <script>
    let shdr;

    function preload() {
      var vertexShaderSource = document.querySelector("#vertex-shader").text;
      var fragmentShaderSource = document.querySelector("#fragment-shader").text;
      shdr = createShader(vertexShaderSource, fragmentShaderSource);
    }

    function setup() {
      createCanvas(windowWidth, windowHeight, WEBGL);
      noStroke();
      shader(shdr);
    }

    function draw() {
      shdr.setUniform("resolution", [width, height]);
      shdr.setUniform("time", millis() / 1000.0);
      quad(-1, -1, 1, -1, 1, 1, -1, 1);
    }

    function windowResized() {
      resizeCanvas(windowWidth, windowHeight);
    }
  </script>

</body>

</html>