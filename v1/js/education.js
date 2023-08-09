//var request_id = document.getElementById('request_id').innerHTML;
//var business_type = document.getElementById('business_type').innerHTML;

Vue.use(Vuetable);

var columns = [
    {
        name: '__sequence',
        title: '#',
    },
    {
        name: '__slot:token',
        title: '<span class="navy-blue glyphicon glyphicon-barcode"></span> Token',
        sortField: 'student_token',
    },
    {
        name: 'student_reg_number',
        title: '<span class="navy-blue glyphicon glyphicon-user"></span> Reg. No.',
        sortField: 'student_reg_number'
    },
    {
        name: '__slot:name',
        title: '<span class="navy-blue glyphicon glyphicon-phone"></span>Name',
        sortField: 'student_first_name'
    },
    {
        name: 'institution_name',
        title: '<span class="navy-blue glyphicon glyphicon-home"></span> Institution',
        sortField: 'institution_name'
    },
    {
        name: 'faculty_name',
        title: '<span class="navy-blue glyphicon glyphicon-book"></span> Faculty',
        sortField: 'faculty_name'
    },
    {
        name: 'course_name',
        title: '<span class="navy-blue glyphicon glyphicon-book"></span> Course',
        sortField: 'course_name'
    },
    {
        name: 'student_specialization',
        title: '<span class="navy-blue glyphicon glyphicon-bookmark"></span> Specialization',
        sortField: 'student_specialization'
    },
    {
        name: 'award',
        title: '<span class="navy-blue glyphicon glyphicon-certificate"></span> Award',
        sortField: 'award'
    },
    {
        name: 'course_level',
        title: '<span class="navy-blue glyphicon glyphicon-level-up"></span> Level',
        sortField: 'course_level'
    },
    {
        name: 'student_date_uploaded',
        title: '<span class="orange glyphicon glyphicon-calendar"></span> Created',
        sortField: 'student_date_uploaded',
        callback:'createdAt'
    },
    {
        name: '__slot:actions',
        title: '<span class="navy-blue glyphicon glyphicon-pencil"></span> Action',
    }
];

var moreParams = [];

var vmDashboard = new Vue({
    el: '#education',
    components: {
        'vuetable-pagination': Vuetable.VuetablePagination
    },
    data: {
        name: '',
        shares: '',
        citizenship: '',
        description: '',
        error: '',
        success:'',
        shareholders:[],
        id:'',
        share_percentage: 0,
        share_value:0,
        encumbrances: [],
        encumbrances_description: '',
        encumbrances_date:'',
        currency:'',
        amount:'',
        idnumber:'',
        business_owners:[],

        fields: columns,
        perPage: parseInt(50),
        search: '',
        has_search: false,
        css: {
            table: {
                tableClass: 'table datatable-responsive',
                loadingClass: 'loading',
                ascendingIcon: 'glyphicon glyphicon-chevron-up',
                descendingIcon: 'glyphicon glyphicon-chevron-down',
                handleIcon: 'glyphicon glyphicon-menu-hamburger',
            },
            pagination: {
                infoClass: 'pull-left',
                wrapperClass: 'vuetable-pagination pull-right',
                activeClass: 'btn-primary',
                disabledClass: 'disabled',
                pageClass: 'btn btn-border',
                linkClass: 'btn btn-border',
                icons: {
                    first: '',
                    prev: '',
                    next: '',
                    last: '',
                },
            }
        },
        moreParams: moreParams,
        sortOrder: [
            { field: 'student_id', direction: 'desc' }
        ],

        student_reg_number:'',
        student_first_name:'',
        student_second_name:'',
        student_third_name:'',
        graduation_date:'',
        graduation_congregation:'',
        data_source:'',
        institution: {},
        institutions: [],
        faculty: {},
        faculties: [],
        courses: [],
        course: {},
        specialization: {},
        specializations: [],
        awards: [],
        award: {},
        levels:[],
        level: {}

    },
    mounted: function(){

        var vm = this;
        vm.error = '';
        vm.success = '';
        vm.reload();
        vm.getInstitutions();
        vm.getFaculty();
        vm.getAwards();
        vm.getLevels();

    },
    ready: function(){

        var vm = this;
        vm.error = '';
        vm.success = '';

    },
    watch: {

        faculty: function(newValue,oldValue) {

            this.getCourse();

        },

        course: function(newValue,oldValue) {

            this.getSpeciliazation();

        },

        error: function(newValue,oldValue){

            if(newValue.length > 0 ) {

                new PNotify({
                    title: 'Error Occurred',
                    text: newValue,
                    addclass: 'bg-danger alert-styled-right',
                    type: 'error'
                });
            }
        },

        success: function(newValue,oldValue){

            if(newValue.length > 0 ) {

                new PNotify({
                    title: 'Great',
                    text: newValue,
                    addclass: 'bg-success alert-styled-right',
                    type: 'success'
                });
            }
        }
    },
    methods: {

        searchData: function() {

            if(this.search.length < 4 ){

                // Solid styled right
                new PNotify({
                    title: 'Invalid Search',
                    text: 'Input atleast 4 characters to search',
                    addclass: 'bg-danger alert-styled-right',
                    type: 'error'
                });

                return;
            }

            this.has_search = true;
            this.moreParams['filter'] = this.search;
            this.$refs.vuetable.refresh();
        },
        reset: function() {

            this.has_search = false;
            this.moreParams['filter'] = "";
            this.$refs.vuetable.refresh();
        },
        reload: function() {

            //this.moreParams['start'] = start;
            //this.moreParams['end'] = end;
            //this.moreParams['account'] = this.account;
            //this.moreParams['paybill'] = this.paybill.paybill;

            this.$refs.vuetable.refresh();
            //this.getSummary(start, end);
        },
        onPaginationData (paginationData) {

            this.$refs.pagination.setPaginationData(paginationData)
        },
        onChangePage (page) {

            this.$refs.vuetable.changePage(page)
        },
        resetAlert: function() {

            this.alertMessage = "";
            this.alertType = "";
        },
        warningAlert: function(message) {

            this.alertMessage  = message;
            this.alertType = 'alert-warning';
        },
        successAlert: function(message) {

            this.alertMessage  = message;
            this.alertType = 'alert-success';
        },
        errorAlert: function(message) {

            this.alertMessage  = message;
            this.alertType = 'alert-danger';
        },
        delete: function(rowData) {

            console.log('TO delete '+JSON.stringify(rowData))

        },
        editRow(rowData){

            alert("You clicked edit on"+ JSON.stringify(rowData))
        },
        activateRow(rowData){

            alert("You clicked edit on"+ JSON.stringify(rowData))
        },
        deleteRow(rowData){

            alert("You clicked delete on"+ JSON.stringify(rowData))

        },
        onLoading() {

            console.log('loading... show your spinner here')

        },
        onLoaded() {

            console.log('loaded! .. hide your spinner here')

        },
        formatNumber: function (num) {
            if(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            }
            return num;
        },
        createdAt: function (value) {

            if(value === "" || !value) {

                return "";
            }

            value = value.replace("T"," ");
            value = value.replace("Z","");

            return moment(value).format('DD/MM/YYYY');
        },
        getStatus: function (status) {

            if (parseInt(status) === 0 ) {

                return '<span class="label label-danger">Waiting Approval</span>';
            }

            return '<span class="label label-success">Approved</span>'
        },
        getInstitutions: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                data_type: 'institution',
            };

            axios.post('../../v1/education_api.php',dt)
                .then(function (response) {

                    //console.log('response '+JSON.stringify(response));
                    vm.institutions = response.data.message;

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },
        getFaculty: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                data_type: 'faculty',
            };

            axios.post('../../v1/education_api.php',dt)
                .then(function (response) {

                    vm.faculties = response.data.message;
                    console.log('response '+JSON.stringify(response));

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },
        getAwards: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                data_type: 'award',
            };

            axios.post('../../v1/education_api.php',dt)
                .then(function (response) {

                    vm.awards = response.data.message;
                    console.log('response '+JSON.stringify(response));

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },
        getLevels: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                data_type: 'level',
            };

            axios.post('../../v1/education_api.php',dt)
                .then(function (response) {

                    vm.levels = response.data.message;
                    console.log('response '+JSON.stringify(response));

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },
        getCourse: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                data_type: 'course',
                faculty_name: vm.faculty.faculty_name
            };

            axios.post('../../v1/education_api.php',dt)
                .then(function (response) {

                    vm.courses = response.data.message;
                    console.log('response '+JSON.stringify(response));

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },
        getSpeciliazation: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                data_type: 'specialization',
                course_id: vm.course.course_id,
                course_name: vm.course.course_name
            };

            axios.post('../../v1/education_api.php',dt)
                .then(function (response) {

                    vm.specializations = response.data.message;
                    console.log('response '+JSON.stringify(response));

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },
        createStudentData: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var data = {};
            data.student_reg_number = this.student_reg_number;

            //
            if(!this.student_first_name) {

                var msg = "Please provide student first name";
                vm.error = msg;
                return;
            }
            data.student_first_name = this.student_first_name;



            if(!this.student_second_name) {

                var msg = "Please provide student second name";
                vm.error = msg;
                return;
            }
            data.student_second_name = this.student_second_name;


            data.student_third_name = this.student_third_name;




            if(!this.graduation_date) {

                var msg = "Please provide student graduation date";
                vm.error = msg;
                return;
            }
            data.graduation_date = this.graduation_date;

            data.graduation_congregation = this.graduation_congregation;


            if(!this.institution) {

                var msg = "Please select student institution";
                vm.error = msg;
                return;
            }

            data.inst_code = this.institution.inst_code;
            data.institution_name = this.institution.inst_name;


            if(!this.faculty) {

                var msg = "Please select student faculty";
                vm.error = msg;
                return;
            }
            data.faculty_code = this.faculty.faculty_code;
            data.faculty_name = this.faculty.faculty_name;

            if(!this.course) {

                var msg = "Please select student course";
                vm.error = msg;
                return;
            }
            data.course_code = this.course.course_code;
            data.course_name = this.course.course_name;


            if(!this.specialization) {

                data.specialization_code = "";
                data.student_specialization = "";

            } else {

                data.specialization_code = this.specialization.specialization_code;
                data.student_specialization = this.specialization.specialization_name;

            }

            if(!this.award) {

                var msg = "Please select student award";
                vm.error = msg;
                return;
            }
            data.award_code = this.award.award_code;
            data.award = this.award.award_name;

            if(!this.level) {

                var msg = "Please select student level";
                vm.error = msg;
                return;
            }
            data.level_code = this.level.level_code;
            data.course_level = this.level.level_name;
            data.data_type = "create";

            axios.post('../../v1/education_api.php',data)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.reload();
                    $('#add-student').modal('hide');

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },

        formatDate: function(value){

            return moment(value).format('dddd,DD MMMM YYYY');
        },
        createShareholder: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            if(!this.name) {

                var msg = "Please provide shareholder name";
                vm.error = msg;
                return;
            }

            //
            if(!this.shares) {

                var msg = "Please provide shareholder shares";
                vm.error = msg;
                return;
            }

            if(!this.share_percentage) {

                var msg = "Please provide shareholder percentage";
                vm.error = msg;
                return;
            }

            if(!this.share_value) {

                var msg = "Please provide shareholder value";
                vm.error = msg;
                return;
            }

            if(!this.citizenship) {

                var msg = "Please provide shareholder citizenship";
                vm.error = msg;
                return;
            }

            var dt = {
                request_id: vm.request_id,
                name: vm.name,
                shares: vm.shares,
                description: vm.description,
                citizenship: vm.citizenship,
                share_percentage: vm.share_percentage,
                share_value: vm.share_value,
                data_type: 'create',
            };

            axios.post('../../v1/api.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getShareHolders();
                    $('#add-shareholder').modal('hide');

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });


        },
        updateShareHolder: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            if(!this.name) {

                var msg = "Please provide shareholder name";
                vm.error = msg;
                return;
            }

            if(!this.shares) {

                var msg = "Please provide shareholder shares";
                vm.error = msg;
                return;
            }

            if(!this.citizenship) {

                var msg = "Please provide shareholder citizenship";
                vm.error = msg;
                return;
            }

            if(!this.share_percentage) {

                var msg = "Please provide shareholder percentage";
                vm.error = msg;
                return;
            }

            if(!this.share_value) {

                var msg = "Please provide shareholder value";
                vm.error = msg;
                return;
            }


            var dt = {
                id: vm.id,
                request_id: vm.request_id,
                name: vm.name,
                shares: vm.shares,
                share_percentage: vm.share_percentage,
                share_value: vm.share_value,
                description: vm.description,
                citizenship: vm.citizenship,
                data_type: 'update',
            };

            axios.post('../../v1/api.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getShareHolders();
                    $('#update-shareholder').modal('hide');

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });


        },
        editShareholder:function(id,name,citizenship,shares,share_value,share_percentage,description){

            this.id = id;
            this.name = name;
            this.citizenship = citizenship;
            this.shares = shares;
            this.description = description;
            this.share_value = share_value;
            this.share_percentage = share_percentage;

            $('#update-shareholder').modal('show')

        },
        deleteShares: function(id){

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                id: id,
                request_id: vm.request_id,
                data_type: 'delete',
            };

            axios.post('../../v1/api.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getShareHolders();

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });

        },

        // encumbrances
        getEncumbrances: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                request_id: vm.request_id,
                data_type: 'read',
            };

            axios.post('../../v1/encumbrances.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.encumbrances = response.data.message;

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },
        editEncumbrances:function(id,date,description){

            this.id = id;
            this.encumbrances_description = description;
            this.encumbrances_date = date;

            $('#update-encumbrances').modal('show')

        },
        updateEncumbrances: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            if(!this.encumbrances_description) {

                var msg = "Please provide encumbrances description";
                vm.error = msg;
                return;
            }

            //
            if(!this.encumbrances_date) {

                var msg = "Please provide encumbrances date";
                vm.error = msg;
                return;
            }

            var dt = {
                id: vm.id,
                date: vm.encumbrances_date,
                description: vm.encumbrances_description,
                data_type: 'update',
            };

            axios.post('../../v1/encumbrances.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getEncumbrances();
                    $('#update-encumbrances').modal('hide');

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });


        },
        addEncumbrancesAmount: function(id){

            this.id = id;
            $('#add-encumbrance-amount').modal('show')

        },
        createEncumbrancesAmount: function(){

            var vm = this;

            vm.error = '';
            vm.success = '';

            if(!this.amount) {

                var msg = "Please provide Amount";
                vm.error = msg;
                return;
            }

            //
            if(!this.currency) {

                var msg = "Please provide currency";
                vm.error = msg;
                return;
            }

            var dt = {
                id: vm.id,
                currency: vm.currency,
                amount: vm.amount,
                data_type: 'create_amount',
            };

            axios.post('../../v1/encumbrances.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getEncumbrances();
                    $('#add-encumbrance-amount').modal('hide');

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });


        },
        removeEmcumbraceAmount: function(id){

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                id: id,
                data_type: 'delete_amount',
            };

            axios.post('../../v1/encumbrances.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getEncumbrances();

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });

        },
        removeEmcumbrace: function(id){

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                id: id,
                data_type: 'delete',
            };

            axios.post('../../v1/encumbrances.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getEncumbrances();

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });

        },

        // business owbership
        getBusinessOwners: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                request_id: vm.request_id,
                data_type: 'read',
            };

            axios.post('../../v1/business.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.business_owners = response.data.message;

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },
        createBusinessOwner: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            if(!this.name) {

                var msg = "Please provide shareholder name";
                vm.error = msg;
                return;
            }

            //
            if(!this.citizenship) {

                var msg = "Please provide shareholder shares";
                vm.error = msg;
                return;
            }

            if(!this.idnumber) {

                var msg = "Please provide shareholder percentage";
                vm.error = msg;
                return;
            }

            if(!this.description) {

                var msg = "Please provide shareholder value";
                vm.error = msg;
                return;
            }

            var dt = {
                request_id: vm.request_id,
                name: vm.name,
                idnumber: vm.idnumber,
                description: vm.description,
                citizenship: vm.citizenship,
                data_type: 'create',
            };

            axios.post('../../v1/business.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getBusinessOwners();
                    $('#add-business').modal('hide');

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });
        },
        editBusinessOwner:function(id,name,idnumber,citizenship,description){

            this.id = id;
            this.name = name;
            this.citizenship = citizenship;
            this.idnumber = idnumber;
            this.description = description;

            $('#update-business').modal('show')

        },
        updateBusinessOwner: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            if(!this.name) {

                var msg = "Please provide Owners name";
                vm.error = msg;
                return;
            }

            if(!this.idnumber) {

                var msg = "Please provide ID Number";
                vm.error = msg;
                return;
            }

            if(!this.citizenship) {

                var msg = "Please provide citizenship";
                vm.error = msg;
                return;
            }

            if(!this.description) {

                var msg = "Please provide ownership details";
                vm.error = msg;
                return;
            }


            var dt = {
                id: vm.id,
                request_id: vm.request_id,
                name: vm.name,
                idnumber: vm.idnumber,
                description: vm.description,
                citizenship: vm.citizenship,
                data_type: 'update',
            };

            axios.post('../../v1/business.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getBusinessOwners();
                    $('#update-business').modal('hide');

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });


        },
        deleteBusinessOwner: function(id){

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                id: id,
                request_id: vm.request_id,
                data_type: 'delete',
            };

            axios.post('../../v1/business.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getBusinessOwners();

                })
                .catch(function (error) {

                    console.log(JSON.stringify(error));
                    //checkResponse(error.data.status);

                });

        },

    },
});