export default () => ({
    open: false,
    taskCounter: 0,

    project: {
        title: '',
        description: '',
        tasks: [{
            body: '',
        }],
    },

    errors: {
        title: [],
        description: [],
    },

    titleError: {
        ['x-text'] () {
            return this.errors.title ? this.errors.title[0] : '';
        }
    },

    descriptionError: {
        ['x-text'] () {
            return this.errors.description ? this.errors.description[0] : '';
        }
    },

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

    addTask: {
      ['@click']() {
        this.project.tasks.push({
            body: ''
        });
      }
    },

    dialog: {
        ['x-show']() {
            return  this.open;
        },
    },

    submit: {
        async ['@click.prevent']() {
            const request = new Request('/projects',{
                method: 'POST',
                headers: new Headers([
                    ['X-Requested-With', 'XMLHttpRequest'],
                    ['X-CSRF-TOKEN', document.getElementsByName('_token')[0].value]
                ]),
                body: new FormData(document.getElementById('modal-form')),
            });
            const response = await fetch(request);
            if (response.ok)
                location = await response.json();
            else {
                const jsonResponse = await response.json();
                if (jsonResponse.errors)
                    this.errors = jsonResponse.errors;
                else
                    alert('Something went wrong on the server side');
            }
        }
    }

})
