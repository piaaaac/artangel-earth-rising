panel.plugin("your-project/block-previews", {
  blocks: {
    space_block: {
      computed: {
        sizeValue() {
          let value = this.content.spacesize;
          if (this.content.spacesize === "custom") {
            value = this.content.customsize;
          }
          return value;
        },
        label() {
          return `Space: ${this.sizeValue}`;
        },
        styleObject() {
          return {
            height: this.sizeValue,
            background: "rgba(0,0,0,0.05)",
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            fontSize: "12px",
            color: "#666",
            borderRadius: "4px",
          };
        },
      },
      template: `
        <div @click="open">
          <div :style="styleObject">
            {{ label }}
          </div>
        </div>
      `,
    },
  },
});
