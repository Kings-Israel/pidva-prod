var app = new Vue({
    el: '#dashboard-stats',
    data: {
        packages: {},
    },
    mounted: function() {

        this.getPackages();
    },
    methods: {


    }
})