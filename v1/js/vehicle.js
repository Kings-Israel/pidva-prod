//var request_id = document.getElementById('request_id').innerHTML;
//var business_type = document.getElementById('business_type').innerHTML;

String.prototype.capitalize = function() {
    var c = '';
    var s = this.split('_').join(' ');

    s = s.split(' ');
    for (var i = 0; i < s.length; i++) {

        c+= s[i].charAt(0).toUpperCase() + s[i].slice(1) + ' ';
    }
    return c;
};

Vue.use(Vuetable);

var columns = [
    {
        name: '__sequence',
        title: '#',
    },
    {
        name: 'ref_number',
        title: '<span class="navy-blue glyphicon glyphicon-user"></span> Ref. No.',
        sortField: 'ref_number'
    },
    {
        name: 'date',
        title: '<span class="navy-blue glyphicon glyphicon-home"></span> Date',
        sortField: 'date'
    },
    {
        name: 'registration_number',
        title: '<span class="navy-blue glyphicon glyphicon-book"></span> Reg.No.',
        sortField: 'registration_number'
    },
    {
        name: 'registration_date',
        title: '<span class="navy-blue glyphicon glyphicon-book"></span> Reg. Date',
        sortField: 'registration_date'
    },
    {
        name: 'chassis_number',
        title: '<span class="navy-blue glyphicon glyphicon-bookmark"></span> Chassis No.',
        sortField: 'chassis_number'
    },
    {
        name: 'customs_entry_number',
        title: '<span class="navy-blue glyphicon glyphicon-certificate"></span> Custom Entr. No',
        sortField: 'customs_entry_number'
    },
    {
        name: 'type_of_vehicle',
        title: '<span class="navy-blue glyphicon glyphicon-level-up"></span> Type of Vehicle',
        sortField: 'type_of_vehicle'
    },
    {
        name: 'body_type',
        title: '<span class="navy-blue glyphicon glyphicon-level-up"></span> Body Type',
        sortField: 'body_type'
    },
    {
        name: 'created',
        title: '<span class="orange glyphicon glyphicon-calendar"></span> Created',
        sortField: 'created',
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
        error: '',
        success:'',
        id:'',
        fields: columns,
        perPage: parseInt(50),
        search: '',
        has_search: false,
        css: {
            table: {
                tableClass: 'table datatable-responsive w-auto text-xsmall',
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
            { field: 'id', direction: 'desc' }
        ],

        ref_number:'',
        date:'',
        registration_number:'',
        registration_date:'',
        chassis_number:'',
        customs_entry_number:'',
        type_of_vehicle:'',
        body_type:'',
        date_of_manufacture:'',
        body_colour:'',
        make:'',
        vehicle_model:'',
        number_of_axles:'',
        engine_number:'',
        fuel_type:'',
        rating:'',
        tare_weight:'',
        load_capacity:'',
        number_of_passengers:'',
        vehicle_under_caveat:'',
        conditions:'',
        drive_side:'',
        logbook_no:'',
        logbook_serial_no:'',
        current_owners:[],
        previous_owners:[],
        owner: {
            id:'',
            name:'',
            pin:'',
            email:''
        },
        vehicle:{}

    },
    mounted: function(){

        var vm = this;
        vm.error = '';
        vm.success = '';
        vm.getVehicle();
        vm.reload();
    },
    ready: function(){

        var vm = this;
        vm.error = '';
        vm.success = '';
        vm.getVehicle();

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

        getVehicle: function(){

            if($("#number_plate")) {

                var number_plate = $("#number_plate").text();

                var vm = this;

                vm.error = '';
                vm.success = '';

                var dt = {
                    data_type: 'read',
                    registration_number: number_plate
                };

                axios.post('../../v1/vehicle_api.php',dt)
                    .then(function (response) {

                        vm.vehicle = response.data.message;
                        console.log('response '+JSON.stringify(response));

                    })
                    .catch(function (error) {

                        console.log(JSON.stringify(error));
                        //checkResponse(error.data.status);

                    });

            }
        },
        addCurrentOwner: function(){

            this.current_owners.push(this.owner);

        },
        addPreviousOwner: function(){

            this.previous_owners.push(this.owner);

        },
        getName: function(s){

            return s.capitalize();

        },
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

            if(this.$refs.vuetable)
                this.$refs.vuetable.refresh();
        },
        reset: function() {

            this.has_search = false;
            this.moreParams['filter'] = "";

            if(this.$refs.vuetable)
                this.$refs.vuetable.refresh();
        },
        reload: function() {

            //this.moreParams['start'] = start;
            //this.moreParams['end'] = end;
            //this.moreParams['account'] = this.account;
            //this.moreParams['paybill'] = this.paybill.paybill;

            //this.$refs.vuetable.refresh();
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

            if(!this.ref_number) {

                var msg = "Please provide "+this.getName('ref_number');
                vm.error = msg;
                return;
            }
            data.ref_number = this.ref_number;

            if(!this.date) {

                var msg = "Please provide "+this.getName('date');
                vm.error = msg;
                return;
            }
            data.date = this.date;

            if(!this.registration_number) {

                var msg = "Please provide "+this.getName('registration_number');
                vm.error = msg;
                return;
            }
            data.registration_number = this.registration_number;



            if(!this.registration_date) {

                var msg = "Please provide "+this.getName('registration_date');
                vm.error = msg;
                return;
            }
            data.registration_date = this.registration_date;

            if(!this.chassis_number) {

                var msg = "Please provide "+this.getName('chassis_number');
                vm.error = msg;
                return;
            }
            data.chassis_number = this.chassis_number;

            if(!this.customs_entry_number) {

                var msg = "Please provide "+this.getName('customs_entry_number');
                vm.error = msg;
                return;
            }
            data.customs_entry_number = this.customs_entry_number;



            if(!this.type_of_vehicle) {

                var msg = "Please provide "+this.getName('type_of_vehicle');
                vm.error = msg;
                return;
            }
            data.type_of_vehicle = this.type_of_vehicle;

            if(!this.body_type) {

                var msg = "Please provide "+this.getName('body_type');
                vm.error = msg;
                return;
            }
            data.body_type = this.body_type;

            if(!this.date_of_manufacture) {

                var msg = "Please provide "+this.getName('date_of_manufacture');
                vm.error = msg;
                return;
            }
            data.date_of_manufacture = this.date_of_manufacture;



            if(!this.body_colour) {

                var msg = "Please provide "+this.getName('body_colour');
                vm.error = msg;
                return;
            }
            data.body_colour = this.body_colour;

            if(!this.make) {

                var msg = "Please provide "+this.getName('make');
                vm.error = msg;
                return;
            }
            data.make = this.make;

            if(!this.vehicle_model) {

                var msg = "Please provide "+this.getName('vehicle_model');
                vm.error = msg;
                return;
            }
            data.vehicle_model = this.vehicle_model;



            if(!this.number_of_axles) {

                var msg = "Please provide "+this.getName('number_of_axles');
                vm.error = msg;
                return;
            }
            data.number_of_axles = this.number_of_axles;

            if(!this.engine_number) {

                var msg = "Please provide "+this.getName('engine_number');
                vm.error = msg;
                return;
            }
            data.engine_number = this.engine_number;

            if(!this.fuel_type) {

                var msg = "Please provide "+this.getName('fuel_type');
                vm.error = msg;
                return;
            }
            data.fuel_type = this.fuel_type;



            if(!this.rating) {

                var msg = "Please provide "+this.getName('rating');
                vm.error = msg;
                return;
            }
            data.rating = this.rating;

            if(!this.tare_weight) {

                var msg = "Please provide "+this.getName('tare_weight');
                vm.error = msg;
                return;
            }
            data.tare_weight = this.tare_weight;

            if(!this.load_capacity) {

                var msg = "Please provide "+this.getName('load_capacity');
                vm.error = msg;
                return;
            }
            data.load_capacity = this.load_capacity;



            if(!this.number_of_passengers) {

                var msg = "Please provide "+this.getName('number_of_passengers');
                vm.error = msg;
                return;
            }
            data.number_of_passengers = this.number_of_passengers;

            if(!this.vehicle_under_caveat) {

                var msg = "Please provide "+this.getName('vehicle_under_caveat');
                vm.error = msg;
                return;
            }
            data.vehicle_under_caveat = this.vehicle_under_caveat;

            if(!this.conditions) {

                var msg = "Please provide "+this.getName('conditions');
                vm.error = msg;
                return;
            }
            data.conditions = this.conditions;



            if(!this.drive_side) {

                var msg = "Please provide "+this.getName('drive_side');
                vm.error = msg;
                return;
            }
            data.drive_side = this.drive_side;

            if(!this.logbook_no) {

                var msg = "Please provide "+this.getName('logbook_no');
                vm.error = msg;
                return;
            }
            data.logbook_no = this.logbook_no;

            if(!this.logbook_serial_no) {

                var msg = "Please provide "+this.getName('logbook_serial_no');
                vm.error = msg;
                return;
            }
            data.logbook_serial_no = this.logbook_serial_no;


            data.current_owners = [];

            $.each(this.current_owners,function(k,v){

                var nameID = vm.getID('co_name',k);
                var name = $("#"+nameID).val();

                var id = vm.getID('co_id',k);
                var idn = $("#"+id).val();


                var pinID = vm.getID('co_pin',k);
                var pin = $("#"+pinID).val();


                var emailID = vm.getID('co_email',k);
                var email = $("#"+emailID).val();

                var owner = {
                    id_number:idn,
                    pin:pin,
                    name:name,
                    email:email
                };

                data.current_owners.push(owner);

            });


            data.previous_owners = [];

            $.each(this.previous_owners,function(k,v){

                var nameID = vm.getID('po_name',k);
                var name = $("#"+nameID).val();

                var id = vm.getID('po_id',k);
                var idn = $("#"+id).val();


                var pinID = vm.getID('po_pin',k);
                var pin = $("#"+pinID).val();


                var emailID = vm.getID('po_email',k);
                var email = $("#"+emailID).val();

                var owner = {
                    id_number:idn,
                    pin:pin,
                    name:name,
                    email:email
                };

                data.previous_owners.push(owner);

            });

            data.data_type = "create";
            data.created_by = $("#created_by").val();

            axios.post('../../v1/vehicle_api.php',data)
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

        getID: function(name,index){

            return name+'-'+index;

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