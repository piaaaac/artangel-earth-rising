<?php
// Simple PHP script to serve a p5.js shader page
$pageTitle = "P5.js Animated Noise Shader";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <!-- Import p5.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.6.0/p5.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #000;
        }

        canvas {
            display: block;
        }
    </style>
</head>

<body>
    <?php
    // You can add PHP-generated content here if needed
    ?>

    <script>
        // Create a shader variable
        let theShader;

        // Preload function - nothing to preload
        function preload() {
            // We create the shader in setup
        }

        function setup() {
            // Create a full browser window sized canvas with WEBGL renderer
            createCanvas(windowWidth, windowHeight, WEBGL);

            // Create the shader with vertex and fragment code
            theShader = createShader(vertexShader, fragmentShader);

            // Disable default rendering style
            noStroke();
        }

        function draw() {
            // EDIT THESE PARAMETERS TO CONTROL THE NOISE APPEARANCE
            // =====================================================

            // How zoomed in/out the noise pattern appears (higher = zoom out)
            let NOISE_SCALE = 1.0;

            // How fast the noise animates along the z-axis (higher = faster)
            let NOISE_SPEED = 0.2;
            NOISE_SPEED = map(mouseY, 0, height, 0.001, 1);

            // Number of detail layers (1-8, higher = more detailed but slower)
            let NOISE_OCTAVES = 2;

            // How much detail increases each octave (usually 2.0)
            let NOISE_LACUNARITY = 2.0;

            // How much influence each octave has (0.0-1.0, usually 0.5)
            let NOISE_PERSISTENCE = 0.8;

            // Color 1 for the noise gradient (RGB 0-1)
            let COLOR1 = [0.0, 0.0, 0.0]; // black

            // Color 2 for the noise gradient (RGB 0-1)
            let COLOR2 = [1.0, 0.0, 0.0]; // red

            // =====================================================

            // Clear background and reset transformations
            background(0);
            resetMatrix();

            // Apply the shader
            shader(theShader);

            // Pass parameters to the shader
            theShader.setUniform("u_resolution", [width, height]);
            theShader.setUniform("u_time", millis() / 1000.0);

            // Pass noise control parameters to the shader
            theShader.setUniform("u_noiseScale", NOISE_SCALE);
            theShader.setUniform("u_noiseSpeed", NOISE_SPEED);
            theShader.setUniform("u_octaves", NOISE_OCTAVES);
            theShader.setUniform("u_lacunarity", NOISE_LACUNARITY);
            theShader.setUniform("u_persistence", NOISE_PERSISTENCE);

            // Pass colors to the shader
            theShader.setUniform("u_color1", COLOR1);
            theShader.setUniform("u_color2", COLOR2);

            // Draw a rectangle that covers the whole canvas
            quad(-1, -1, 1, -1, 1, 1, -1, 1);
        }

        function windowResized() {
            resizeCanvas(windowWidth, windowHeight);
        }

        // Define the vertex shader
        const vertexShader = `
            attribute vec3 aPosition;
            attribute vec2 aTexCoord;
            
            varying vec2 vTexCoord;
            
            void main() {
                // Copy the position data into a vec4, adding 1.0 as the w parameter
                vec4 positionVec4 = vec4(aPosition, 1.0);
                
                // Send the vertex information on to the fragment shader
                gl_Position = positionVec4;
                
                // Pass the texture coordinates to the fragment shader
                vTexCoord = aTexCoord;
            }
        `;

        // Define the fragment shader
        const fragmentShader = `
            precision mediump float;
            
            varying vec2 vTexCoord;
            
            uniform vec2 u_resolution;
            uniform float u_time;
            
            // Noise control parameters
            uniform float u_noiseScale;     // Controls the scale/zoom of the noise
            uniform float u_noiseSpeed;     // Controls the speed of z-axis animation
            uniform int u_octaves;          // Number of noise octaves (detail level)
            uniform float u_lacunarity;     // How much detail is added each octave
            uniform float u_persistence;    // How much influence each octave has
            
            // Color parameters
            uniform vec3 u_color1;          // First color for gradient
            uniform vec3 u_color2;          // Second color for gradient
            
            // 2D Random function
            float random(vec2 st) {
                return fract(sin(dot(st.xy, vec2(12.9898, 78.233))) * 43758.5453123);
            }
            
            // 3D Noise function (2D + time as z-axis)
            float noise(vec2 st, float z) {
                vec2 i = floor(st);
                vec2 f = fract(st);
                
                // Create two layers of noise at different z positions
                float z1 = floor(z);
                float z2 = z1 + 1.0;
                
                // Eight corners for 3D interpolation (2 layers of 4 corners each)
                float a1 = random(vec2(i.x, i.y) + vec2(z1 * 0.11, z1 * 0.17));
                float b1 = random(vec2(i.x + 1.0, i.y) + vec2(z1 * 0.11, z1 * 0.17));
                float c1 = random(vec2(i.x, i.y + 1.0) + vec2(z1 * 0.11, z1 * 0.17));
                float d1 = random(vec2(i.x + 1.0, i.y + 1.0) + vec2(z1 * 0.11, z1 * 0.17));
                
                float a2 = random(vec2(i.x, i.y) + vec2(z2 * 0.11, z2 * 0.17));
                float b2 = random(vec2(i.x + 1.0, i.y) + vec2(z2 * 0.11, z2 * 0.17));
                float c2 = random(vec2(i.x, i.y + 1.0) + vec2(z2 * 0.11, z2 * 0.17));
                float d2 = random(vec2(i.x + 1.0, i.y + 1.0) + vec2(z2 * 0.11, z2 * 0.17));
                
                // Smooth interpolation
                vec2 u = smoothstep(0.0, 1.0, f);
                
                // Mix the first layer (z1)
                float mix1 = mix(mix(a1, b1, u.x), mix(c1, d1, u.x), u.y);
                
                // Mix the second layer (z2)
                float mix2 = mix(mix(a2, b2, u.x), mix(c2, d2, u.x), u.y);
                
                // Interpolate between the two z-layers
                float fz = fract(z);
                return mix(mix1, mix2, smoothstep(0.0, 1.0, fz));
            }
            
            // Fractal Brownian Motion with time as z-axis
            float fbm(vec2 st, float time) {
                float value = 0.0;
                float amplitude = 0.5;
                float frequency = 1.0;
                
                // Add octaves of 3D noise (using time for z-axis)
                for (int i = 0; i < 8; i++) {  // Limit to 8 max octaves
                    if(i >= u_octaves) break;  // Use only the requested number of octaves
                    
                    value += amplitude * noise(st * frequency, time * u_noiseSpeed);
                    st *= u_lacunarity;        // Increase frequency by lacunarity factor
                    time *= 1.3;               // Different time scaling for each octave
                    amplitude *= u_persistence; // Decrease amplitude by persistence factor
                }
                
                return value;
            }
            
            void main() {
                // Flip the y-coordinate
                vec2 st = vTexCoord;
                
                // Adjust aspect ratio
                st.x *= u_resolution.x / u_resolution.y;
                
                // Scale the coordinate system by the noise scale parameter
                st *= u_noiseScale;
                
                // Static coordinate system (no movement)
                // We'll use time only for z-axis animation
                
                // Get fbm noise value with time as z-axis
                float n = fbm(st, u_time);
                
                // Map noise to colors
                // vec3 color = mix(u_color1, u_color2, n);
                vec3 color = n < 0.5 ? u_color1 : u_color2;
                
                // Add some variation based on time
                color += 0.05 * sin(n * 10.0 + u_time);
                
                // Output the final color
                gl_FragColor = vec4(color, 1.0);
            }
        `;
    </script>
</body>

</html>