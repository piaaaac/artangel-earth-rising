<!-- via chat gpt --- kinda sucks -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>2D Dithered Noise Shader</title>
  <style>
    body {
      margin: 0;
      overflow: hidden;
    }

    canvas {
      display: block;
    }
  </style>
</head>

<body>
  <canvas id="glcanvas"></canvas>
  <script type="x-shader/x-fragment" id="fragShader">
    precision highp float;

uniform vec2 u_resolution;
uniform float u_time;

// Simple 2D pseudo-random noise
float random(vec2 st) {
    return fract(sin(dot(st.xy, vec2(12.9898,78.233))) * 43758.5453123);
}

// Bayer 4x4 matrix for dithering
float bayerDither(vec2 pos) {
    int x = int(mod(pos.x, 4.0));
    int y = int(mod(pos.y, 4.0));
    int index = y * 4 + x;

    float threshold = 0.0;
    if (index ==  0) threshold = 0.0 / 16.0;
    if (index ==  1) threshold = 8.0 / 16.0;
    if (index ==  2) threshold = 2.0 / 16.0;
    if (index ==  3) threshold =10.0 / 16.0;
    if (index ==  4) threshold =12.0 / 16.0;
    if (index ==  5) threshold = 4.0 / 16.0;
    if (index ==  6) threshold =14.0 / 16.0;
    if (index ==  7) threshold = 6.0 / 16.0;
    if (index ==  8) threshold = 3.0 / 16.0;
    if (index ==  9) threshold =11.0 / 16.0;
    if (index == 10) threshold = 1.0 / 16.0;
    if (index == 11) threshold = 9.0 / 16.0;
    if (index == 12) threshold =15.0 / 16.0;
    if (index == 13) threshold = 7.0 / 16.0;
    if (index == 14) threshold =13.0 / 16.0;
    if (index == 15) threshold = 5.0 / 16.0;

    return threshold;
}

void main() {
    vec2 uv = gl_FragCoord.xy / u_resolution.xy;

    // Scale and animate the UVs
    vec2 st = uv * 10.0;
    st += vec2(u_time * 0.5, u_time * 0.5);

    float n = random(floor(st)); // Tiled random noise

    // Apply Bayer dithering
    float dither = bayerDither(gl_FragCoord.xy);
    float outputColor = step(dither, n); // Binary: 0 or 1

    gl_FragColor = vec4(vec3(outputColor), 1.0);
}
</script>

  <script type="x-shader/x-vertex" id="vertShader">
    attribute vec4 a_position;
void main() {
  gl_Position = a_position;
}
</script>

  <script>
    const canvas = document.getElementById("glcanvas");
    const gl = canvas.getContext("webgl");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    // Compile shader
    function compileShader(type, source) {
      const shader = gl.createShader(type);
      gl.shaderSource(shader, source);
      gl.compileShader(shader);
      if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
        console.error(gl.getShaderInfoLog(shader));
        return null;
      }
      return shader;
    }

    const vertShader = compileShader(gl.VERTEX_SHADER, document.getElementById("vertShader").text);
    const fragShader = compileShader(gl.FRAGMENT_SHADER, document.getElementById("fragShader").text);

    const program = gl.createProgram();
    gl.attachShader(program, vertShader);
    gl.attachShader(program, fragShader);
    gl.linkProgram(program);
    gl.useProgram(program);

    // Fullscreen quad
    const positionBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, positionBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([
      -1, -1, 1, -1, -1, 1,
      -1, 1, 1, -1, 1, 1,
    ]), gl.STATIC_DRAW);

    const positionLocation = gl.getAttribLocation(program, "a_position");
    gl.enableVertexAttribArray(positionLocation);
    gl.vertexAttribPointer(positionLocation, 2, gl.FLOAT, false, 0, 0);

    // Uniform locations
    const resolutionLocation = gl.getUniformLocation(program, "u_resolution");
    const timeLocation = gl.getUniformLocation(program, "u_time");

    // Animation loop
    let startTime = performance.now();

    function render() {
      const time = (performance.now() - startTime) * 0.001;

      gl.viewport(0, 0, canvas.width, canvas.height);
      gl.uniform2f(resolutionLocation, canvas.width, canvas.height);
      gl.uniform1f(timeLocation, time);

      gl.drawArrays(gl.TRIANGLES, 0, 6);
      requestAnimationFrame(render);
    }
    render();
  </script>
</body>

</html>