<script>
  let stars = [];
  let numStars = 500;

  function setup() {
    createCanvas(windowWidth, windowHeight);
    for (let i = 0; i < numStars; i++) {
      stars.push(new Star());
    }
  }

  function draw() {
    background(0);

    // Map mouseY to speed: top = slow, bottom = fast
    let speed = map(mouseY, 0, height, 0, 25);

    translate(width / 2, height / 2);

    for (let star of stars) {
      star.update(speed);
      star.show();
    }
  }

  class Star {
    constructor() {
      this.reset();
    }

    reset() {
      this.x = random(-width, width);
      this.y = random(-height, height);
      this.z = random(width);
      this.pz = this.z;
    }

    update(speed) {
      this.z -= speed;
      if (this.z < 1) {
        this.reset();
        this.z = width;
        this.pz = this.z;
      }
    }

    show() {
      fill(255);
      noStroke();

      let sx = map(this.x / this.z, 0, 1, 0, width);
      let sy = map(this.y / this.z, 0, 1, 0, height);

      let r = map(this.z, 0, width, 8, 0);
      ellipse(sx, sy, r, r);

      // Draw motion trail
      let px = map(this.x / this.pz, 0, 1, 0, width);
      let py = map(this.y / this.pz, 0, 1, 0, height);

      this.pz = this.z;

      stroke(255);
      line(px, py, sx, sy);
    }
  }
</script>