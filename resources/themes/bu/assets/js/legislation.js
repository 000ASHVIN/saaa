Vue.component('app-legislation-index-screen', {

    props: {
        actlist: ''
    },

    computed: {
        filteredList() {
          return this.actlist.filter(acts => {
            return acts.name.toLowerCase().includes(this.search.toLowerCase())
          })
        }
      },
    methods: {
       
    }
});
