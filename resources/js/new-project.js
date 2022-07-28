export default () => ({
    errors: {
        title: 'Something went wrong with the title',
        description: 'Something went wrong with the description',
    },

    titleError: {
        ['x-text'] () {
            return this.errors.title;
        }
    },

    descriptionError: {
        ['x-text'] () {
            return this.errors.description;
        }
    },

    open: false,

    close: {
      ['@click.outside'] () {
          this.open = ! this.open;
      },
    },

    trigger: {
        ['@click']() {
            this.open = ! this.open;
        },
    },

    dialog: {
        ['x-show']() {
            return  this.open;
        },
    },

})
