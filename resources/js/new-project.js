export default () => ({
    open: false,

    trigger: {
        ['@click']() {
            this.open = ! this.open
        },
    },

    dialog: {
        ['x-show']() {
            return  this.open;
        },
    },

})
