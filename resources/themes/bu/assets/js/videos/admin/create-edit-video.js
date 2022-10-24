Vue.component('create-edit-video', {

    props: ['videoProviders'],

    data: function () {
        var ajaxStates = {
            none: 0,
            busy: 1,
            success: 2,
            error: 3
        };
        return {
            ajaxStates: ajaxStates,
            fetchState: ajaxStates.none,
            selectedVideoProviderId: this.videoProviders[0].id,
            form: {
                title: '',
                reference: '',
                videoProviderId: this.videoProviders[0].id,
                downloadLink: '',
                width: '',
                height: '',
                hours: '',
                tag: '',
                amount: '',
                description: '',
            }
        };
    },

    ready: function () {
        var vm = this;
        vm.fetchProviderVideos(vm.selectedVideoProviderId);
    },

    computed: {
        selectedVideoProvider: function () {
            return this.getSelectedVideoProvider();
        },
        selectedVideo: function () {
            return this.getSelectedVideo();
        }
    },

    methods: {
        apply: function () {
            var vm = this;
            vm.form.title = vm.selectedVideo.title;
            vm.form.reference = vm.selectedVideo.reference;
            vm.form.videoProviderId = vm.selectedVideoProviderId;
            vm.form.downloadLink = vm.selectedVideo.downloadLink;
            vm.form.width = vm.selectedVideo.width;
            vm.form.height = vm.selectedVideo.height;
            vm.form.hours = vm.selectedVideo.hours;
            vm.form.tag = vm.selectedVideo.tag;
            vm.form.amount = vm.selectedVideo.amount;
            vm.form.description = vm.selectedVideo.description;
        },
        retry: function () {
            var vm = this;
            vm.fetchProviderVideos(vm.selectedVideoProviderId);
        },
        fetchProviderVideos: function (id) {
            var vm = this;
            vm.fetchState = vm.ajaxStates.busy;
            vm.$http({
                url: '/admin/video-providers/' + id + '/videos',
                method: 'GET'
            }).then(
                function (response) {
                    vm.fetchState = vm.ajaxStates.success;
                    Vue.set(vm.selectedVideoProvider, 'videos', response.data);
                    vm.selectedVideoReference = vm.selectedVideoProvider.videos[0].reference;
                },
                function (response) {
                    vm.fetchState = vm.ajaxStates.error;
                    console.log('error', response);
                }
            );
        },
        changeVideoProviderSelection: function () {
            var vm = this;
            vm.fetchProviderVideos(vm.selectedVideoProviderId);
        },
        getVideoProviderById: function (id) {
            return _.find(this.videoProviders, {id: id});
        },
        getSelectedVideoProvider: function () {
            return this.getVideoProviderById(this.selectedVideoProviderId);
        },
        getVideoByReference: function (reference) {
            return _.find(this.selectedVideoProvider.videos, {reference: reference});
        },
        getSelectedVideo: function () {
            return this.getVideoByReference(this.selectedVideoReference);
        }
    }
});
