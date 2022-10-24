Vue.component('sync-course', {
    props: {
        courses: null,
        moodle_courses: null,
        moodle_course_ids: null
    },
    ready: function ready() {},

    data: function data() {
        return {
            course: 0,
            moodle_course: 0,
            messages: {
                text: '',
                class: ''
            },
            courseMessage: false,
            moodleMessage: false,
            type: '',
            busy: false,
            allowAccess: false
        };
    },

    computed: {
        syncedMessage() {
            return "This course is already synced with moodle, on sync it will sync course on moodle with system course.";
        },
        notSyncedMessage() {
            return "This course will be created on moodle.";
        },
        syncedMoodleMessage() {
            return "This course is already available in our system and our system data will be altered, so please verify before sync.";
        },
        notSyncedMoodleMessage() {
            return "This course will be created on our system.";
        },
    },

    methods: {
        checkCourse() {
            this.courseMessage = true;
            this.moodleMessage = false;
            this.messages = {
                text: '',
                class: ''
            };
            if(this.course > 0 || this.moodle_course > 0) {
                this.allowAccess = true;
            } else {
                this.allowAccess = false;
            }
            if(this.course == 0) {
                return;
            }
            const course = this.courses.find(course => course.id == this.course)
            if(this.moodle_course_ids.includes(course.moodle_course_id)) {
                this.messages.text = this.syncedMessage;
                this.messages.class = 'alert-success';

                this.moodle_course = course.moodle_course_id;
            } else {
                this.messages.text = this.notSyncedMessage;
                this.messages.class = 'alert-info';

                this.moodle_course = 0;
            }
        },

        checkMoodleCourse() {
            this.courseMessage = false;
            this.moodleMessage = true;
            this.messages = {
                text: '',
                class: ''
            };
            if(this.course > 0 || this.moodle_course > 0) {
                this.allowAccess = true;
            } else {
                this.allowAccess = false;
            }
            if(this.moodle_course == 0) {
                return;
            }
            const course = this.courses.find(course => course.moodle_course_id == this.moodle_course);
            
            if(course) {
                this.messages.text = this.syncedMoodleMessage;
                this.messages.class = 'alert-warning';

                this.course = course.id;
                this.type = 'update';
            } else {
                this.messages.text = this.notSyncedMoodleMessage;
                this.messages.class = 'alert-info';

                this.course = 0;
                this.type = 'create';
            }
        },

        syncCourse() {
            this.busy = true;
            let data = {};
            if(this.courseMessage) {
                data = {
                    course: this.course
                };
            }
            if(this.moodleMessage) {
                data = {
                    course: this.moodle_course,
                    is_moodle_course: true,
                    type: this.type
                };
            }
            
            this.$http.post('/admin/course/sync', data)
                .success((response) => {
                    if(response[0] == 'success') {
                        this.busy = false;
                        window.location.reload(true);
                    }
                })
                .error((errors) => {
                    this.busy = false;
                });
        }
    }
});