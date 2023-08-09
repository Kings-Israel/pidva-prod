var request_id = document.getElementById('request_id').innerHTML;
var business_type = document.getElementById('business_type').innerHTML;

var vmDashboard = new Vue({
    el: '#company',
    data: {
        name: '',
        shares: '',
        citizenship: '',
        description: '',
        request_id: request_id,
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
        business_type: business_type,
        business_owners:[]
    },
    mounted: function(){

        var vm = this;
        vm.error = '';
        vm.success = '';

        if(this.business_type === "LIMITED COMPANY") {

            vm.getShareHolders();
            vm.getEncumbrances();

        }
        else if(this.business_type === "BUSINESS") {

            vm.getBusinessOwners();
        }
    },
    ready: function(){

        var vm = this;
        vm.error = '';
        vm.success = '';

        if(this.business_type === "LIMITED COMPANY") {

            vm.getShareHolders();
            vm.getEncumbrances();

        }
        else if(this.business_type === "BUSINESS") {

            vm.getBusinessOwners();
        }
    },
    methods: {

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
        getShareHolders: function () {

            var vm = this;

            vm.error = '';
            vm.success = '';

            var dt = {
                request_id: vm.request_id,
                data_type: 'read',
            };

            axios.post('../../v1/api.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.shareholders = response.data.message;

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
        createEncumbrances: function () {

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
                request_id: vm.request_id,
                date: vm.encumbrances_date,
                description: vm.encumbrances_description,
                data_type: 'create',
            };

            axios.post('../../v1/encumbrances.php',dt)
                .then(function (response) {

                    console.log('response '+JSON.stringify(response));
                    vm.success = response.data.message;
                    vm.getEncumbrances();
                    $('#add-encumbrances').modal('hide');

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