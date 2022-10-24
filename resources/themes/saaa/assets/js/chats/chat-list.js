Vue.component('chat-list', {
    props: {
        user: null
    },

    data: function () {
        return {
            rooms: ''
        }
    },

    created () {
        this.fetchChatsList();
        this.timer = setInterval(this.fetchChatsList, 30000);
    },

    methods: {
        fetchChatsList () {
            this.$http.get('/admin/chat/list', (res) => {
                this.rooms = res.rooms;
                
                var i = 1;
                this.rooms.map((room) => {
                    room.index = i;
                    i++;
                });
            });
        },
    }
});