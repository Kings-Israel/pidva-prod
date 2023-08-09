$(document).ready(function () {

    var a = {
        pel_psmt_request: {},
        pel_psmt_employ_data: [],
        pel_individual_id: [],
        pel_individual_fprint_data: [],
        pel_company_registration: [],
        pel_company_license: [],
        pel_company_shares_data: [],
        pel_company_shares_data_comm: [],
        pel_company_credit_data: [],
        pel_credit_data_comments: [],
        pel_company_tax_data: [],
        pel_data_proff_membership: [],
        pel_company_customer_ref: [],
        pel_data_residence: [],
        pel_data_social_media: [],
        pel_company_watchlist_data: [],
        pel_individual_credit_data: [],
        pel_individual_criminal_data: [],
        pel_individual_tax_data: [],
        pel_individual_dl_data: [],
        pel_individual_psv_data: [],
        pel_psmt_edu_data: [],
        pel_individual_gap_data: [],
        pel_individual_watchlist_data: []
    };

    function getHash(length) {

        var ClientCompanyID = document.getElementById('client-company-id').innerHTML;

        var characters = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
        var result = '';
        var charactersLength = characters.length;

        for (var i = 0; i < length; i++) {

            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        var hr = today.getHours();
        var min = today.getMinutes();
        var sec = today.getSeconds();

        var dt = dd + '' + mm + '' + yyyy + '' + hr + '' + min + '' + sec;

        return ClientCompanyID + '-RQ-' + '-' + dt + '-' + result;
    }

    var baseURL = window.location.protocol + '//' + window.location.hostname + "/";

    if ($('#dashboard-stats').length) {

        var dashboardStats = new Vue({
            el: '#dashboard-stats',
            data: {
                stats: {},
                packages: {},
                bulkRequests: [],
                page: 'dashboard',
                printing: false,
            },
            mounted: function () {

                this.getStats();
                this.getPackages();

                setInterval(function () {

                    $.each($('[id^="request-id-"]'),function(k,v){

                        var percentage = v.getAttribute('percentage');//.split('%').join('');
                        var id = v.getAttribute('id');

                        $("#"+id).animate({

                            width: percentage

                        }, 1000 );

                    })

                },1000 * 3)
            },
            computed: {

                closed: function () {

                    var ids = 0;
                    var vm = this;

                    $.each(vm.report.pel_individual_credit_data, function (k, v) {

                        if (v.loan_status == "CLOSED") {

                            ids++;
                        }

                    });

                    return ids;
                },
                open: function () {

                    var ids = 0;
                    var vm = this;

                    $.each(vm.report.pel_individual_credit_data, function (k, v) {

                        if (v.loan_status == "OPEN") {

                            ids++;
                        }

                    });

                    return ids;
                },
                id: function () {

                    var ids = {};
                    var vm = this;

                    $.each(vm.report.pel_individual_id, function (k, v) {

                        if (v.identity_type === "NATIONAL IDENTITY") {

                            ids = v;
                        }

                    });

                    return ids;
                },
                passport: function () {

                    var ids = {};
                    var vm = this;

                    $.each(vm.report.pel_individual_id, function (k, v) {

                        if (v.identity_type === "PASSPORT") {

                            ids = v;
                        }

                    });

                    return ids;
                }

            },
            methods: {

                printer: function(){

                    var vm = this;

                    var name = document.getElementById('company-name').innerHTML;

                    var data = {
                        name: name
                    };

                    console.log('am there');

                    var baseURL = window.location.protocol + '//' + window.location.hostname + "/";
                    var ur = baseURL + 'v1/api/Printer.php';

                    vm.printing = true;

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.printing = false;

                            console.log('GOT response '+JSON.stringify(response.data));
                            var dataUrl =response.data.file;

                            if(!dataUrl) {

                                swal({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong! please try again',
                                });

                                return;
                            }
                            //window.location.href = dataUrl;
                            //return;


                            // Construct the 'a' element
                            var link = document.createElement("a");
                            link.download = "consent-form.docx";
                            link.target = "_blank";

                            // Construct the URI
                            link.href = dataUrl;
                            document.body.appendChild(link);
                            link.click();

                            // Cleanup the DOM
                            document.body.removeChild(link);
                            delete link;


                        })
                        .catch(function (error) {

                            vm.printing = false;

                            console.log('GOT response error '+JSON.stringify(error))

                        });
                },

                addRequest: function() {

                    var vm = this;

                    var key = this.bulkRequests.length;
                    var obj = {
                        package_id: '',
                        documents:[]
                    };

                    vm.bulkRequests[key] = obj;

                },
                selectPackage: function(){



                },
                financial: function (data) {

                    if (!data) {

                        return 0;
                    }

                    if (!isNaN(parseFloat(data)) && isFinite(data)) {

                        return new Intl.NumberFormat('en-IN', {maximumSignificantDigits: 3}).format(data);

                        return parseFloat(data).toFixed(2)

                    }

                    return 0;

                },
                getStatus: function (status) {

                    switch (status) {

                        case '00':
                            return 'New Request';

                        case '11':
                            return 'Final Report';

                        case '33':
                            return 'Interim';

                        case '44':
                            return 'In Progress';

                        case '55':
                            return 'Awaiting Quotation';

                        case '66':
                            return 'Awaiting Payment';
                    }

                },
                getStats: function () {

                    var vm = this;

                    var login_id = document.getElementById('login-id').innerHTML;

                    var data = {
                        type: 'stats',
                        login_id: login_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.stats = response.data;

                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                ifnull: function (data, defaults) {

                    if (!data || data == undefined || data === "" || data.length == 0 ) {

                        return defaults;
                    }

                    return data;

                },
                getPackages: function () {

                    var vm = this;

                    var client_id = document.getElementById('client-id').innerHTML;

                    var data = {
                        type: 'packages',
                        client_id: client_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.packages = response.data;

                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                getPackageURL: function (item) {

                    return baseURL + "request.php?package_id=" + item.package_id;
                }
            }
        });

    }

    if ($('#report-stats').length) {

        var reportStats = new Vue({
            el: '#report-stats',
            data: {
                stats: {},
                packages: {},
                requests: [],
                requestRows: [],
                perRow: 3,
                sortByStatus:'',
                sortableRequests: [],
                page: 'report',
            },
            mounted: function () {

                this.getStats();
                this.getRequests();
            },
            watch: {

                sortByStatus: function(n,o) {


                },

            },
            computed: {

                closed: function () {

                    var ids = 0;
                    var vm = this;

                    $.each(vm.report.pel_individual_credit_data, function (k, v) {

                        if (v.loan_status == "CLOSED") {

                            ids++;
                        }

                    });

                    return ids;
                },
                open: function () {

                    var ids = 0;
                    var vm = this;

                    $.each(vm.report.pel_individual_credit_data, function (k, v) {

                        if (v.loan_status == "OPEN") {

                            ids++;
                        }

                    });

                    return ids;
                },
                id: function () {

                    var ids = {};
                    var vm = this;

                    $.each(vm.report.pel_individual_id, function (k, v) {

                        if (v.identity_type === "NATIONAL IDENTITY") {

                            ids = v;
                        }

                    });

                    return ids;
                },
                passport: function () {

                    var ids = {};
                    var vm = this;

                    $.each(vm.report.pel_individual_id, function (k, v) {

                        if (v.identity_type === "PASSPORT") {

                            ids = v;
                        }

                    });

                    return ids;
                }

            },
            methods: {
                getReportURL: function(r){

                    return '/viewrequest.php?requestid='+r.request_id;

                },
                createdAt: function (value) {

                    if (!value) {

                        return '-';

                    } else {

                        return moment(value).format('Do MMMM YYYY, h:mm a');
                    }
                },
                addRequest: function() {

                    var vm = this;

                    var key = this.bulkRequests.length;
                    var obj = {
                        package_id: '',
                        documents:[]
                    };

                    vm.bulkRequests[key] = obj;

                },
                selectPackage: function(){



                },
                financial: function (data) {

                    if (!data) {

                        return 0;
                    }

                    if (!isNaN(parseFloat(data)) && isFinite(data)) {

                        return new Intl.NumberFormat('en-IN', {maximumSignificantDigits: 3}).format(data);

                    }

                    return 0;

                },
                getProgressText: function(percentage){

                    return percentage+' Completed';

                },
                getStatus: function (status) {

                    switch (status) {

                        case '00':
                            return 'New Request';

                        case '11':
                            return 'Final Report';

                        case '33':
                            return 'Interim';

                        case '44':
                            return 'In Progress';

                        case '55':
                            return 'Awaiting Quotation';

                        case '66':
                            return 'Awaiting Payment';
                    }

                },
                getStats: function () {

                    var vm = this;

                    var login_id = document.getElementById('login-id').innerHTML;

                    var data = {
                        type: 'stats',
                        login_id: login_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.stats = response.data;

                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                getStatusClass: function(status) {

                    return "small_status_"+status;

                },
                getStatusName: function(status) {

                    var report_name = "";

                    switch (status) {

                        case '44':
                            report_name = "In Progress";
                            break;

                        case '00':
                            report_name = "New Request";
                            break;

                        case '11':
                            report_name = "Final";
                            break;

                        case '33':
                            report_name = "Interim";
                            break;

                        case '22':
                        case '55':
                        case '66':
                            report_name = "Pending";
                            break;

                        default:
                            report_name = "All";
                            break;

                    }

                    return report_name;

                },
                ifnull: function (data, defaults) {

                    if (!data || data == undefined || data === "" || data.length == 0 ) {

                        return defaults;
                    }

                    return data;

                },
                getProgressColor: function(p) {

                    var percentage = p.split('%').join('');

                    if(parseInt(p) < 33 ) {

                        return 'bg-danger';
                    }

                    if(parseInt(p) > 66 ) {

                        return 'bg-success';
                    }

                    return 'bg-warning';

                },
                animate: function(){

                    $.each($('[id^="request-id-"]'),function(k,v){

                        var percentage = v.getAttribute('percentage');//.split('%').join('');
                        var id = v.getAttribute('id');

                        $("#"+id).animate({

                            width: percentage

                        }, 2500 );

                    })
                },
                displayRequests: function(){

                    var vm = this;

                    vm.requestRows = [];
                    console.log('GOT sortByStatus '+vm.sortByStatus);


                    var requests = vm.requests;
                    vm.sortableRequests = [];

                    // make into rows
                    var i = 0;
                    var r  = 0;
                    var x = [];

                    $.each(requests,function(k,v){

                        if(vm.sortByStatus.trim().length > 0 ) {

                            if(v.status != vm.sortByStatus) {

                                return true;

                            }
                        }

                        vm.sortableRequests.push(v);

                        v.id = "request-id-"+v.request_id;

                        i++;
                        x.push(v);

                        if (i % vm.perRow === 0 ) {

                            r++;
                            var obj = {
                                batch: r,
                                request: x
                            };
                            vm.requestRows.push(obj);

                            x  = [];
                        }

                    });

                    if(x.length > 0 ) {

                        r++;
                        var obj = {
                            batch: r,
                            request: x
                        };
                        vm.requestRows.push(obj);

                        x  = [];
                    }

                    //console.log(JSON.stringify(vm.requestRows));

                    setTimeout(function () {

                        vm.animate();

                    },1000 * 2);

                },
                getRequests: function () {

                    var vm = this;

                    var client_id = document.getElementById('login-id').innerHTML;

                    var data = {
                        type: 'client-requests',
                        client_id: client_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.requestRows = [];

                            vm.requests = response.data;
                            vm.sortableRequests = response.data;

                            vm.displayRequests(vm.sortableRequests);

                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                getPackageURL: function (item) {

                    return baseURL + "request.php?package_id=" + item.package_id;
                }
            }
        });
    }

    if ($('#sidebar-vm').length) {

        var sidebarVM = new Vue({
            el: '#sidebar-vm',
            data: {
                packages: {},
            },
            mounted: function () {

                this.getPackages();
            },
            methods: {

                getPackages: function () {

                    var vm = this;

                    var client_id = document.getElementById('client-id').innerHTML;

                    var data = {
                        type: 'packages',
                        client_id: client_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.packages = response.data;

                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                getPackageURL: function (item) {

                    return baseURL + "request.php?package_id=" + item.package_id;
                }
            }
        });
    }

    if ($('#request-vm').length) {

        var requestVM = new Vue({
            el: '#request-vm',
            data: {
                stats: {},
                package: {},
                request_id: -1,
                hash_length: 4,
                countries: [],
                module_id: [],
                documents: [],
                terms: false,
                request_ref_number: '',
                request_plan: '',
                bg_dataset_name: '',
                bg_dataset_email: '',
                bg_dataset_mobile: '',
                dataset_citizenship: 'KENYA',
                client_id: '',
                user_login_id: '',
                client_company_id: '',
                staff_id: '',
                uploaded_by: '',
                bg_dataset_idnumber: '',
                required_documents: [],
                sampleModules: ['Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya','Credit Check kenya']
            },
            mounted: function () {

                this.init();
            },
            computed: {

                closed: function () {

                    var ids = 0;
                    var vm = this;

                    $.each(vm.report.pel_individual_credit_data, function (k, v) {

                        if (v.loan_status == "CLOSED") {

                            ids++;
                        }

                    });

                    return ids;
                },
                open: function () {

                    var ids = 0;
                    var vm = this;

                    $.each(vm.report.pel_individual_credit_data, function (k, v) {

                        if (v.loan_status == "OPEN") {

                            ids++;
                        }

                    });

                    return ids;
                },
                id: function () {

                    var ids = {};
                    var vm = this;

                    $.each(vm.report.pel_individual_id, function (k, v) {

                        if (v.identity_type === "NATIONAL IDENTITY") {

                            ids = v;
                        }

                    });

                    return ids;
                },
                passport: function () {

                    var ids = {};
                    var vm = this;

                    $.each(vm.report.pel_individual_id, function (k, v) {

                        if (v.identity_type === "PASSPORT") {

                            ids = v;
                        }

                    });

                    return ids;
                }

            },
            watch: {

                module_id: function (n, o) {

                    //console.log('NEW Values '+n);

                },
            },
            methods: {
                init: function () {

                    var rid = document.getElementById('package-id').innerHTML;
                    this.client_id = document.getElementById('client-id').innerHTML;
                    this.user_login_id = document.getElementById('login-id').innerHTML;
                    this.client_company_id = document.getElementById('client-company-id').innerHTML;
                    this.staff_id = document.getElementById('staff_id').innerHTML;
                    this.uploaded_by = document.getElementById('uploaded_by').innerHTML;

                    this.request_id = rid;
                    this.getStats();
                    this.getPackages();
                    this.getCountries();
                    this.request_ref_number = this.hash();
                },
                setModuleID: function () {


                    this.getDocuments();

                },
                getCountries: function () {

                    var vm = this;
                    var data = {type: 'country'};

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.countries = response.data;

                        })
                        .catch(function (error) {

                            console.log(error);

                        });

                },
                hash: function () {

                    var length = this.hash_length;

                    var ClientCompanyID = document.getElementById('client-company-id').innerHTML;

                    var characters = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
                    var result = '';
                    var charactersLength = characters.length;

                    for (var i = 0; i < length; i++) {

                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }

                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth() + 1;
                    var yyyy = today.getFullYear();
                    var hr = today.getHours();
                    var min = today.getMinutes();
                    var sec = today.getSeconds();

                    var dt = yyyy + '' + mm + '' + dd + '' + hr + '' + min + '' + sec;

                    return ClientCompanyID + '-RQ-' + today.valueOf() + '-' + result;

                },
                financial: function (data) {

                    if (!data) {

                        return 0;
                    }

                    if (!isNaN(parseFloat(data)) && isFinite(data)) {

                        return new Intl.NumberFormat('en-IN', {maximumSignificantDigits: 3}).format(data);

                        return parseFloat(data).toFixed(2)

                    }

                    return 0;

                },
                getStatus: function (status) {

                    switch (status) {

                        case '00':
                            return 'New Request';

                        case '11':
                            return 'Final Report';

                        case '33':
                            return 'Interim';

                        case '44':
                            return 'In Progress';

                        case '55':
                            return 'Awaiting Quotation';

                        case '66':
                            return 'Awaiting Payment';
                    }

                },
                getStats: function () {

                    var vm = this;

                    var login_id = document.getElementById('login-id').innerHTML;

                    var data = {
                        type: 'stats',
                        login_id: login_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.stats = response.data;

                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                ifnull: function (data, defaults) {

                    if (!data || data == undefined || data === "") {

                        return defaults;
                    }

                    return data;

                },
                getPackages: function () {

                    var vm = this;

                    var data = {
                        type: 'package',
                        request_id: vm.request_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.package = response.data;

                            $.each(vm.package.modules, function (k, v) {

                                var id = 'package_id-' + v.package_id + '-module_id-' + v.module_id;
                                v.id = id;
                                vm.package.modules[k] = v;
                            });

                            console.log('GOT JSON ' + JSON.stringify(vm.package.modules));


                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                getPackageURL: function (item) {

                    return baseURL + "request.php?package_id=" + item.package_id;
                },
                getClassType: function (d) {

                    switch (d) {

                        case 'text':
                            return 'txbx';
                            break;

                        case 'file':
                            return 'flbx';
                            break;
                    }

                    return 'txbx';

                },
                getDocuments: function () {

                    var vm = this;

                    var data = {
                        type: 'documents',
                        module_id: vm.module_id.join(',')
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.documents = response.data;

                            vm.required_documents = [];

                            $.each(vm.documents, function (key, value) {

                                $.each(value.documents, function (k, v) {

                                    var id = 'package_id-' + v.package_id + '-module_id-' + v.module_id + '-module_doc_id-' + v.module_doc_id;
                                    var classs = 'class-package_id-' + v.package_id + '-module_id-' + v.module_id;

                                    v.id = id;
                                    v.class = classs;

                                    value.documents[k] = v;

                                    vm.required_documents.push(v);

                                });

                                vm.documents[key] = value;

                            });

                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                postForm: function () {

                    var vm = this;

                    this.request_plan = this.package.package_name;
                    //this.request_plan = this.package.package_cost;


                    // Turn of Progress bar on
                    // $.notify('I have a progress bar', { showProgressbar: true });

                    // Update Progress bar
                    //var notify = $.notify('<strong>Saving</strong> Do not close this page...', { allow_dismiss: false });
                    //notify.update({ type: 'warning', '<strong>Oops</strong> Something happened. Correcting Now', progress: 20 });

                    const formData1 = new FormData();
                    formData1.append("request_type", "general");

                    // check terms agreement
                    if (!this.terms) {

                        this.setAlert('error', 'Terms and Conditions', 'Please accept terms and conditions to proceed');
                        //notify.update('<strong>Failed</strong> Please accept terms and conditions',{ type: 'warning'});
                        return;

                    }

                    // check terms agreement
                    if (!this.bg_dataset_name) {

                        //notify.update('<strong>Failed</strong> Please enter name',{ type: 'warning'});
                        this.setAlert('error', 'Candidate name', 'Please enter candidate name to proceed');
                        return;
                    }

                    formData1.append("bg_dataset_name", this.bg_dataset_name);

                    if (!this.dataset_citizenship) {

                        //notify.update('<strong>Failed</strong> Please select citizenship',{ type: 'warning'});
                        this.setAlert('error', 'Candidate Citizenship', 'Please select candidate citizenship to proceed');
                        return;
                    }

                    if (!$('#consentform')[0].files[0]) {

                        //notify.update('<strong>Failed</strong> Please consent form',{ type: 'warning'});
                        this.setAlert('error', 'Consent Form', 'Please pick consent form to proceed');
                        return;
                    }

                    var cost = this.getCost();

                    formData1.append('consentform', $('#consentform')[0].files[0]);
                    formData1.append('file_name', 'consentform');
                    formData1.append('cost', cost);

                    formData1.append("dataset_citizenship", this.dataset_citizenship);

                    var formValues = [];

                    formValues.push(formData1);

                    // get general fields

                    var package_id = document.getElementById('package-id').innerHTML;

                    var hasError = false;

                    $.each(vm.module_id, function (key, value) {

                        var classs = 'class-package_id-' + package_id + '-module_id-' + value;

                        // get all the required fields and check for data
                        var docs = vm.getModuleDocuments(value);

                        var userValues = [];

                        var formData = new FormData();
                        formData.append("request_type", 'module');

                        if (docs.documents.length === 0) {

                            formData.append('data_type', "none");
                            formData.append('module_id', value);
                            formData.append('module_name', docs.module_name);
                            formValues.push(formData);
                        }

                        $.each(docs.documents, function (k, v) {

                            var formData = new FormData();
                            formData.append("request_type", 'module');

                            var data_type = v.data_type;
                            var id = v.id;
                            var document_name = v.document_name;
                            var module_name = v.module_name;
                            var module_doc_id = v.module_doc_id;

                            formData.append('data_type', data_type);
                            formData.append('document_name', document_name);
                            formData.append('module_name', module_name);
                            formData.append('module_id', value);
                            formData.append('module_doc_id', module_doc_id);

                            switch (data_type) {

                                case "text":

                                    var userValue = $('#' + id).val();

                                    if (!userValue) {

                                        hasError = true;
                                        //notify.update('<strong>Failed</strong> Missing '+document_name+' for '+document_name,{ type: 'warning'});
                                        vm.setAlert('error', document_name, 'Please enter ' + document_name + ' for ' + module_name);

                                        return false
                                    }

                                    v.value = userValue;
                                    formData.append('value', userValue);
                                    break;

                                case "file":

                                    if (!$('#' + id)[0].files[0]) {

                                        hasError = true;
                                        //notify.update('<strong>Failed</strong> Missing '+document_name+' for '+document_name,{ type: 'warning'});
                                        vm.setAlert('error', document_name, 'Please select ' + document_name + ' for ' + module_name);

                                        return false
                                    }

                                    formData.append(id, $('#' + id)[0].files[0]);
                                    formData.append('file_name', id);

                            }

                            formValues.push(formData);

                        });

                        if (hasError) {

                            return false;
                        }

                    });

                    if (hasError) {

                        return;
                    }

                    var notify = $.notify('<strong>Submitting...</strong> Validating form...', {
                        allow_dismiss: false,
                        showProgressbar: true
                    });

                    notify.update('submitting your request', {type: 'success', progress: 20});

                    var request = [];
                    var ur = baseURL + 'v1/api/Uploads.php';

                    $.each(formValues, function (k, v) {

                        v.append("request_ref_number", vm.request_ref_number);
                        v.append('package_id', package_id);
                        v.append('client_id', vm.client_id);
                        v.append('user_id', vm.user_login_id);
                        v.append('client_company_id', vm.client_company_id);
                        v.append('staff_id', vm.staff_id);
                        v.append('request_id', vm.request_id);
                        v.append('uploaded_by', vm.uploaded_by);
                        v.append('request_plan', vm.request_plan);

                        var x = $.ajax({
                            url: ur,
                            type: 'POST',
                            data: v,
                            processData: false, // tel jQuery not to process the data
                            contentType: false, // tell jQuery not to set contentType
                        });

                        request.push(x);
                    });

                    swal({
                        title: "Submitting Request",
                        text: "Your request is being submitted",
                        Icon: baseURL + 'assets/loader.svg',
                    });

                    $.when.apply($, request).then(function () {

                        var formData = new FormData();
                        formData.append('submit', 'submit');

                        // Things to do when all is done
                        console.log('Then ');

                        var x = $.ajax({
                            url: ur,
                            type: 'POST',
                            data: formData,
                            processData: false, // tell jQuery not to process the data
                            contentType: false, // tell jQuery not to set contentType
                            success: function (data) {

                                notify.update('completed!!', {type: 'success', progress: 100});
                                notify.close();

                                swal({
                                    title: "Request successful submitted",
                                    text: "Request has been successfully submitted",
                                    type: "success",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d8e95d",
                                    confirmButtonText: "Another Request",
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: false,
                                    cancelButtonText: "Track Progress",
                                    cancelButtonColor: "#255468"
                                }, function (isConfirm) {

                                    swal.close();

                                    if (isConfirm) {

                                        vm.init();
                                    } else {

                                        var url = baseURL + 'dashboard/'
                                        window.location.replace(url);
                                    }
                                });

                            },
                            error: function (jqXHR, textStatus, error) {

                                //vmDeposits.upload_loader = '';
                                //console.log(" got response "+JSON.stringify());
                                swal({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong! please try again',
                                })

                            }
                        });

                    });

                },
                getModuleDocuments: function (mid) {

                    var vm = this;
                    var obj = {documents: [], module_id: mid, module_name : ''};

                    $.each(vm.documents, function (k, v) {

                        if (parseInt(v.module_id) === parseInt(mid)) {

                            obj = v;
                            return false;
                        }
                    });

                    if(obj.module_name === '' || !obj.module_name) {

                        // get module name
                        $.each(vm.package.modules, function (k, v) {

                            if (parseInt(v.module_id) === parseInt(mid)) {

                                obj.module_name = v.module_name;
                            }
                        });
                    }

                    return obj;
                },
                getCost: function () {

                    var vm = this;
                    var cost = 0;

                    $.each(vm.module_id, function (key, value) {

                        $.each(vm.package.modules, function (k, v) {

                            if (parseInt(v.module_id) === parseInt(value)) {

                                cost += parseInt(v.module_cost);
                            }
                        });

                    });

                    return cost;
                },
                setAlert: function (type, title, message) {

                    swal(
                        title,
                        message,
                        type
                    )

                }
            }
        });

    }

    if ($('#bulk-request-vm').length) {

        var bulkRequestVM = new Vue({
            el: '#bulk-request-vm',
            data: {
                stats: {},
                package: {},
                request_id: -1,
                hash_length: 4,
                countries: [],
                module_id: [],
                documents: [],
                terms: false,
                request_ref_number: '',
                request_plan: '',
                bg_dataset_name: '',
                bg_dataset_email: '',
                bg_dataset_mobile: '',
                dataset_citizenship: 'KENYA',
                client_id: '',
                user_login_id: '',
                client_company_id: '',
                staff_id: '',
                uploaded_by: '',
                bg_dataset_idnumber: '',
                required_documents: [],
                clientPackages: [],
                selectedPackage: {modules:[]},
                selectedPackageID: '',
                package_id: '',
                bulkRequests:[],
                percentage: 0,
                total_uploads: 0,
                already_uploaded: 0,
                pb: ''
            },
            mounted: function () {

                this.init();
            },
            computed: {

                closed: function () {

                    var ids = 0;
                    var vm = this;

                    $.each(vm.report.pel_individual_credit_data, function (k, v) {

                        if (v.loan_status == "CLOSED") {

                            ids++;
                        }

                    });

                    return ids;
                },
                open: function () {

                    var ids = 0;
                    var vm = this;

                    $.each(vm.report.pel_individual_credit_data, function (k, v) {

                        if (v.loan_status == "OPEN") {

                            ids++;
                        }

                    });

                    return ids;
                },
                id: function () {

                    var ids = {};
                    var vm = this;

                    $.each(vm.report.pel_individual_id, function (k, v) {

                        if (v.identity_type === "NATIONAL IDENTITY") {

                            ids = v;
                        }

                    });

                    return ids;
                },
                passport: function () {

                    var ids = {};
                    var vm = this;

                    $.each(vm.report.pel_individual_id, function (k, v) {

                        if (v.identity_type === "PASSPORT") {

                            ids = v;
                        }

                    });

                    return ids;
                }

            },
            watch: {

                selectedPackageID: function (n, o) {

                    console.log('NEW Values '+n);

                },
                already_uploaded: function (n) {

                    this.percentage = parseInt(n/this.total_uploads) * 100;
                },
                percentage: function (n) {

                    this.pb.setValue(n);
                }
            },
            methods: {
                init: function () {

                    var rid = document.getElementById('package-id').innerHTML;
                    this.client_id = document.getElementById('client-id').innerHTML;
                    this.user_login_id = document.getElementById('login-id').innerHTML;
                    this.client_company_id = document.getElementById('client-company-id').innerHTML;
                    this.staff_id = document.getElementById('staff_id').innerHTML;
                    this.uploaded_by = document.getElementById('uploaded_by').innerHTML;

                    this.request_id = rid;
                    this.getStats();
                    this.getPackages();
                    this.getClientPackages();
                    this.getCountries();
                    this.request_ref_number = this.hash();
                },
                getIndexedID: function(index,id){

                    return 'i'+index+'_'+id;

                },
                addVerification: function(){

                    var request_id = this.hash();
                    var vm = this;
                    var object = {

                        selectedPackage: vm.selectedPackage,
                        request_ref_number: request_id,
                        bg_dataset_name: '',
                        dataset_citizenship: 'KENYA',
                        terms: false,
                        package_name: ''
                    };

                    this.bulkRequests.push(object);

                },
                getClientPackages: function(){

                    var vm = this;

                    var data = {
                        type: 'client-package',
                        client_id: vm.client_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.selectedPackage = {};

                            vm.clientPackages = response.data;

                            $.each(vm.clientPackages,function (k,v) {

                                $.each(v.documents,function(kk,vv){

                                    var id = 'package_id-' + vv.package_id + '-module_id-' + vv.module_id + '-module_doc_id-' + vv.module_doc_id;
                                    var classs = 'class-package_id-' + vv.package_id + '-module_id-' + vv.module_id;
                                    vv.id = id;
                                    vv.class = classs;
                                    v.documents[kk] = vv;
                                });

                                vm.clientPackages[k] = v;
                            });

                            vm.selectedPackage = vm.clientPackages[0];
                            vm.selectedPackageID = vm.selectedPackage.package_id;
                            vm.addVerification();

                        })
                        .catch(function (error) {

                            console.log(error);

                        });

                },
                setSelectedPackageID: function () {

                    var vm = this;

                    $.each(vm.clientPackages,function (k,v) {

                        if(parseInt(v.package_id) === parseInt(vm.selectedPackageID)) {

                            vm.selectedPackage = v;
                        }
                    });

                    //this.getDocuments();
                },
                getCountries: function () {

                    var vm = this;
                    var data = {type: 'country'};

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.countries = response.data;

                        })
                        .catch(function (error) {

                            console.log(error);

                        });

                },
                hash: function () {

                    var length = this.hash_length;

                    var ClientCompanyID = document.getElementById('client-company-id').innerHTML;

                    var characters = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
                    var result = '';
                    var charactersLength = characters.length;

                    for (var i = 0; i < length; i++) {

                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }

                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth() + 1;
                    var yyyy = today.getFullYear();
                    var hr = today.getHours();
                    var min = today.getMinutes();
                    var sec = today.getSeconds();

                    var dt = yyyy + '' + mm + '' + dd + '' + hr + '' + min + '' + sec;

                    return ClientCompanyID + '-RQ-' + today.valueOf() + '-' + result;

                },
                financial: function (data) {

                    if (!data) {

                        return 0;
                    }

                    if (!isNaN(parseFloat(data)) && isFinite(data)) {

                        return new Intl.NumberFormat('en-IN', {maximumSignificantDigits: 3}).format(data);

                        return parseFloat(data).toFixed(2)

                    }

                    return 0;

                },
                getStatus: function (status) {

                    switch (status) {

                        case '00':
                            return 'New Request';

                        case '11':
                            return 'Final Report';

                        case '33':
                            return 'Interim';

                        case '44':
                            return 'In Progress';

                        case '55':
                            return 'Awaiting Quotation';

                        case '66':
                            return 'Awaiting Payment';
                    }

                },
                getStats: function () {

                    var vm = this;

                    var login_id = document.getElementById('login-id').innerHTML;

                    var data = {
                        type: 'stats',
                        login_id: login_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.stats = response.data;

                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                ifnull: function (data, defaults) {

                    if (!data || data == undefined || data === "") {

                        return defaults;
                    }

                    return data;

                },
                getPackages: function () {

                    var vm = this;

                    var data = {
                        type: 'package',
                        request_id: vm.request_id
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.package = response.data;

                            $.each(vm.package.modules, function (k, v) {

                                var id = 'package_id-' + v.package_id + '-module_id-' + v.module_id;
                                v.id = id;
                                vm.package.modules[k] = v;
                            });

                            console.log('GOT JSON ' + JSON.stringify(vm.package.modules));


                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                getPackageURL: function (item) {

                    return baseURL + "request.php?package_id=" + item.package_id;
                },
                getClassType: function (d) {

                    switch (d) {

                        case 'text':
                            return 'txbx';
                            break;

                        case 'file':
                            return 'flbx';
                            break;
                    }

                    return 'txbx';

                },
                getDocuments: function () {

                    var vm = this;

                    var data = {
                        type: 'documents',
                        module_id: vm.module_id.join(',')
                    };

                    var ur = baseURL + 'v1/api/Dashboard.php';

                    axios.post(ur, data)
                        .then(function (response) {

                            vm.documents = response.data;

                            vm.required_documents = [];

                            $.each(vm.documents, function (key, value) {

                                $.each(value.documents, function (k, v) {

                                    var id = 'package_id-' + v.package_id + '-module_id-' + v.module_id + '-module_doc_id-' + v.module_doc_id;
                                    var classs = 'class-package_id-' + v.package_id + '-module_id-' + v.module_id;

                                    v.id = id;
                                    v.class = classs;

                                    value.documents[k] = v;

                                    vm.required_documents.push(v);

                                });

                                vm.documents[key] = value;

                            });

                        })
                        .catch(function (error) {

                            console.log(error);

                        });
                },
                postForm: async function (index) {

                    var bulk = this.bulkRequests[index];
                    var rc = parseInt(index) + 1;

                    var vm = this;

                    var package_name = bulk.selectedPackage.package_name;
                    var package_id = bulk.selectedPackage.package_id;
                    var cost = bulk.selectedPackage.cost;

                    const formData1 = new FormData();
                    formData1.append("request_type", "general");
                    formData1.append('cost', cost);
                    formData1.append('package_id', package_id);
                    formData1.append('package_name', package_name);

                    // check terms agreement
                    if (!bulk.terms) {

                        this.setAlert('error', 'Terms and Conditions', 'Please accept terms and conditions for  to proceed');
                        //notify.update('<strong>Failed</strong> Please accept terms and conditions',{ type: 'warning'});
                        return;

                    }

                    // check terms agreement
                    if (!bulk.bg_dataset_name) {

                        //notify.update('<strong>Failed</strong> Please enter name',{ type: 'warning'});
                        this.setAlert('error', 'Candidate name', 'Please enter candidate name to proceed');
                        return;
                    }
                    formData1.append("bg_dataset_name", bulk.bg_dataset_name);

                    if (!bulk.dataset_citizenship) {

                        //notify.update('<strong>Failed</strong> Please select citizenship',{ type: 'warning'});
                        this.setAlert('error', 'Candidate Citizenship', 'Please select candidate citizenship to proceed');
                        return;
                    }
                    formData1.append("dataset_citizenship", bulk.dataset_citizenship);
                    /*
                                        if (!$('#consentform')[0].files[0]) {

                                            notify.update('<strong>Failed</strong> Please consent form',{ type: 'warning'});
                                            this.setAlert('error', 'Consent Form', 'Please pick consent form to proceed');
                                            return;
                                        }

                                        formData1.append('consentform', $('#consentform')[0].files[0]);
                                        formData1.append('file_name', 'consentform');
                    */

                    var formValues = [];

                    formValues.push(formData1);

                    // get general fields

                    var hasError = false;

                    $.each(bulk.selectedPackage.documents, function (key, value) {

                        var module_id = value.module_id;
                        var data_type = value.data_type;
                        var mandatory_status = value.mandatory_status;
                        var module_doc_id = value.module_doc_id;
                        var module_name = value.module_name;
                        var document_name = value.document_name;
                        var id = value.id;

                        console.log('GOT ID HERE AS '+id);

                        var classs = 'class-package_id-' + package_id + '-module_id-' + module_id;
                        var formData = new FormData();
                        formData.append("request_type", 'module');
                        formData.append('data_type', data_type);
                        formData.append('document_name', document_name);
                        formData.append('module_name', module_name);
                        formData.append('module_id', module_id);
                        formData.append('module_doc_id', module_doc_id);

                        var ids = '#i'+index+'_'+ id;

                        if(data_type === "text") {

                            var userValue = $(ids).val();

                            console.log('got text value for '+document_name+' id '+ids+' value '+userValue);
                            if (!userValue || userValue == undefined || userValue.length === 0) {

                                hasError = true;
                                //notify.update('<strong>Failed</strong> Missing '+document_name+' for '+document_name,{ type: 'warning'});
                                vm.setAlert('error', document_name, 'Please enter ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }

                            formData.append('value', userValue);
                        }

                        if(data_type === "date") {

                            var userValue = $(ids).val();

                            console.log('got text value for '+document_name+' id '+ids+' value '+userValue);
                            if (!userValue || userValue == undefined || userValue.length === 0) {

                                hasError = true;
                                //notify.update('<strong>Failed</strong> Missing '+document_name+' for '+document_name,{ type: 'warning'});
                                vm.setAlert('error', document_name, 'Please enter ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }

                            formData.append('value', userValue);
                        }

                        if(data_type === "number") {

                            var userValue = $(ids).val();

                            console.log('got text value for '+document_name+' id '+ids+' value '+userValue);
                            if (!userValue || userValue == undefined || userValue.length === 0) {

                                hasError = true;
                                //notify.update('<strong>Failed</strong> Missing '+document_name+' for '+document_name,{ type: 'warning'});
                                vm.setAlert('error', document_name, 'Please enter ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }

                            formData.append('value', userValue);
                        }

                        if(data_type === "email") {

                            var userValue = $(ids).val();

                            console.log('got text value for '+document_name+' id '+ids+' value '+userValue);
                            if (!userValue || userValue == undefined || userValue.length === 0 || ValidateEmail(userValue) ) {

                                hasError = true;
                                vm.setAlert('error', document_name, 'Please enter ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }
                        }

                        if(data_type === "file") {

                            if (!$(ids)[0].files[0]) {

                                console.log('missing file value for '+document_name+' id '+ids);

                                hasError = true;
                                //notify.update('<strong>Failed</strong> Missing '+document_name+' for '+document_name,{ type: 'warning'});
                                vm.setAlert('error', document_name, 'Please select ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }
                            console.log('got file value for '+document_name+' id '+ids+' value '+$(ids)[0].files[0].name);

                            formData.append(id, $(ids)[0].files[0]);
                            formData.append('file_name', id);
                        }

                        formValues.push(formData);

                        if (hasError) {

                            return false;
                        }
                    });

                    if (hasError) {

                        return;
                    }

                    var notify = $.notify('<strong>Request Number '+index+1+' Submitting...</strong> Validating form...', {
                        allow_dismiss: false,
                        showProgressbar: true
                    });

                    notify.update('submitting your request', {type: 'success', progress: 20});

                    var request = [];
                    var ur = baseURL + 'v1/api/BulkUploads.php?index='+index;

                    $.each(formValues, function (k, v) {

                        v.append("request_ref_number", bulk.request_ref_number);
                        v.append('package_id', package_id);
                        v.append('client_id', vm.client_id);
                        v.append('user_id', vm.user_login_id);
                        v.append('client_company_id', vm.client_company_id);
                        v.append('staff_id', vm.staff_id);
                        v.append('request_id', vm.request_id);
                        v.append('uploaded_by', vm.uploaded_by);
                        v.append('request_plan', package_name);
                        v.append('package_id', package_id);

                        var x = $.ajax({
                            url: ur,
                            type: 'POST',
                            data: v,
                            processData: false, // tell jQuery not to process the data
                            contentType: false, // tell jQuery not to set contentType
                            success: function (data) {

                                bulkRequestVM.already_uploaded++;
                            },
                            error: function (jqXHR, textStatus, error) {

                                bulkRequestVM.already_uploaded++;

                            },
                        });

                        request.push(x);
                    });

                    this.total_uploads = request.length;
                    this.already_uploaded = 0;

                    swal({
                        title: "Submitting Request "+index+1,
                        text: "Your request is being submitted",
                        Icon: baseURL + 'assets/loader.svg',
                    });

                    await vm.postData(vm,request,index,notify);

                    /*
                    $.when.apply($, request).then(function () {

                        var formData = new FormData();
                        formData.append('submit', 'submit');

                        // Things to do when all is done
                        console.log('Then ');

                        var x = $.ajax({
                            url: ur,
                            type: 'POST',
                            data: formData,
                            processData: false, // tell jQuery not to process the data
                            contentType: false, // tell jQuery not to set contentType
                            success: function (data) {

                                notify.update('Request '+index + 1+' completed!!', {type: 'success', progress: 100});
                                notify.close();

                                swal({
                                    title: "Request "+index+1+" successful submitted",
                                    text: "Request has been successfully submitted",
                                    type: "success",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d8e95d",
                                    confirmButtonText: "Another Request",
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: false,
                                    cancelButtonText: "Track Progress",
                                    cancelButtonColor: "#255468"
                                }, function (isConfirm) {

                                    swal.close();

                                    if (isConfirm) {

                                        vm.init();

                                    } else {

                                        var url = baseURL + 'dashboard/'
                                        window.location.replace(url);
                                    }
                                });

                            },
                            error: function (jqXHR, textStatus, error) {

                                //vmDeposits.upload_loader = '';
                                //console.log(" got response "+JSON.stringify());
                                swal({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong! please try again',
                                })

                            }
                        });

                    });
                    */

                },
                validateForm: function (index) {

                    var bulk = this.bulkRequests[index];
                    var vm = this;

                    var package_name = bulk.selectedPackage.package_name;
                    var package_id = bulk.selectedPackage.package_id;
                    var cost = bulk.selectedPackage.cost;

                    // check terms agreement
                    if (!bulk.terms) {

                        this.setAlert('error', 'Terms and Conditions', 'Please accept terms and conditions to proceed');
                        //notify.update('<strong>Failed</strong> Please accept terms and conditions',{ type: 'warning'});
                        return false;

                    }

                    // check terms agreement
                    if (!bulk.bg_dataset_name) {

                        //notify.update('<strong>Failed</strong> Please enter name',{ type: 'warning'});
                        this.setAlert('error', 'Candidate name', 'Please enter candidate name to proceed');
                        return false;
                    }

                    if (!bulk.dataset_citizenship) {

                        //notify.update('<strong>Failed</strong> Please select citizenship',{ type: 'warning'});
                        this.setAlert('error', 'Candidate Citizenship', 'Please select candidate citizenship to proceed');
                        return false;
                    }

                    var formValues = [];

                    // get general fields

                    var hasError = false;

                    $.each(bulk.selectedPackage.documents, function (key, value) {

                        var module_id = value.module_id;
                        var data_type = value.data_type;
                        var mandatory_status = value.mandatory_status;
                        var module_doc_id = value.module_doc_id;
                        var module_name = value.module_name;
                        var document_name = value.document_name;
                        var id = value.id;

                        console.log('GOT ID HERE AS '+id);

                        var classs = 'class-package_id-' + package_id + '-module_id-' + module_id;

                        var ids = '#i'+index+'_'+ id;

                        if(data_type === "text") {

                            var userValue = $(ids).val();

                            console.log('got text value for '+document_name+' id '+ids+' value '+userValue);
                            if (!userValue || userValue == undefined || userValue.length === 0) {

                                hasError = true;
                                vm.setAlert('error', document_name, 'Please enter ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }
                        }

                        if(data_type === "email") {

                            var userValue = $(ids).val();

                            console.log('got text value for '+document_name+' id '+ids+' value '+userValue);
                            if (!userValue || userValue == undefined || userValue.length === 0 || ValidateEmail(userValue) ) {

                                hasError = true;
                                vm.setAlert('error', document_name, 'Please enter ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }
                        }

                        if(data_type === "date") {

                            var userValue = $(ids).val();

                            console.log('got text value for '+document_name+' id '+ids+' value '+userValue);
                            if (!userValue || userValue == undefined || userValue.length === 0) {

                                hasError = true;
                                vm.setAlert('error', document_name, 'Please enter ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }
                        }

                        if(data_type === "number") {

                            var userValue = $(ids).val();

                            console.log('got text value for '+document_name+' id '+ids+' value '+userValue);
                            if (!userValue || userValue == undefined || userValue.length === 0) {

                                hasError = true;
                                vm.setAlert('error', document_name, 'Please enter ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }
                        }

                        if(data_type === "file") {

                            if (!$(ids)[0].files[0]) {

                                console.log('missing file value for '+document_name+' id '+ids);

                                hasError = true;
                                vm.setAlert('error', document_name, 'Please select ' + document_name + ' for ' + module_name+' Request Number '+index+1);

                                return false
                            }
                            console.log('got file value for '+document_name+' id '+ids+' value '+$(ids)[0].files[0].name);
                        }

                        if (hasError) {

                            return false;
                        }
                    });

                    if (hasError) {

                        return false;
                    }

                    return true;
                },
                getModuleDocuments: function (mid) {

                    var vm = this;
                    var obj = {documents: [], module_id: mid, module_name : ''};

                    $.each(vm.documents, function (k, v) {

                        if (parseInt(v.module_id) === parseInt(mid)) {

                            obj = v;
                            return false;
                        }
                    });

                    if(obj.module_name === '' || !obj.module_name) {

                        // get module name
                        $.each(vm.package.modules, function (k, v) {

                            if (parseInt(v.module_id) === parseInt(mid)) {

                                obj.module_name = v.module_name;
                            }
                        });
                    }

                    return obj;
                },
                getCost: function () {

                    var vm = this;
                    var cost = 0;

                    $.each(vm.module_id, function (key, value) {

                        $.each(vm.package.modules, function (k, v) {

                            if (parseInt(v.module_id) === parseInt(value)) {

                                cost += parseInt(v.module_cost);
                            }
                        });

                    });

                    return cost;
                },
                setAlert: function (type, title, message) {

                    swal(
                        title,
                        message,
                        type
                    )

                },
                onSubmit: function() {

                    // validate data
                    var vm = this;

                    var has_no_error = false;
                    var c = 0;

                    $.each(vm.bulkRequests,function(key,value){

                        has_no_error = vm.validateForm(key);
                        if (!has_no_error) {

                            return false;
                        }

                        c  += value.selectedPackage.documents.length;

                    });

                    if (!has_no_error) {

                        return false;
                    }

                    // setup progress
                    const pb=new Progress(),
                        elm=pb.getElement();

                    vm.pb = pb;

                    document.getElementById('progress-area').appendChild(elm);
                    elm.style.cssText='position:absolute;left:calc(50% - 50px);top:calc(50% - 50px)';
                    pb.setValue(0);
                    vm.percentage = 0;
                    vm.total_uploads = c;

                    //$('#progressModal').modal('show');

                    $.each(vm.bulkRequests,function(key,value){

                        vm.postForm(key);

                    });

                },
                postData: function (vm,request,index,notify) {

                    vm = this;

                    var ur = baseURL + 'v1/api/BulkUploads.php?index='+index;

                    return new Promise(function (fulfill, reject) {

                        $.when.apply($, request).then(function () {

                            var formData = new FormData();
                            formData.append('submit', 'submit');

                            // Things to do when all is done
                            console.log('Then ');

                            var x = $.ajax({
                                url: ur,
                                type: 'POST',
                                data: formData,
                                processData: false, // tell jQuery not to process the data
                                contentType: false, // tell jQuery not to set contentType
                                success: function (data) {

                                    bulkRequestVM.already_uploaded++;

                                    fulfill(data);

                                    notify.update('Request '+index + 1+' completed!!', {type: 'success', progress: 100});
                                    notify.close();

                                    swal({
                                        icon: 'success',
                                        title: 'done posting request '+index+1,
                                        text: 'Request '+(index+1)+' posted successfully ',
                                    })

                                },
                                error: function (jqXHR, textStatus, error) {

                                    bulkRequestVM.already_uploaded++;

                                    reject(error);

                                    //vmDeposits.upload_loader = '';
                                    //console.log(" got response "+JSON.stringify());
                                    swal({
                                        icon: 'error',
                                        title: 'Error posting request '+index+1,
                                        text: 'Something went wrong! please try again',
                                    })

                                }
                            });

                        });

                    });
                }
            }
        });

    }

    function postData1(vm,request,index,notify) {

      //  notify.update('submitting your request', {type: 'success', progress: 20});

        var ur = baseURL + 'v1/api/Uploads.php?index='+index;
/*
        swal({
            title: "Submitting Request "+index+1,
            text: "Your request is being submitted",
            Icon: baseURL + 'assets/loader.svg',
        });
*/
        return new Promise(function (fulfill, reject) {

            $.when.apply($, request).then(function () {

                var formData = new FormData();
                formData.append('submit', 'submit');

                // Things to do when all is done
                console.log('Then ');

                var x = $.ajax({
                    url: ur,
                    type: 'POST',
                    data: formData,
                    processData: false, // tell jQuery not to process the data
                    contentType: false, // tell jQuery not to set contentType
                    success: function (data) {

                        bulkRequestVM.already_
                        fulfill(data);

                        notify.update('Request '+index + 1+' completed!!', {type: 'success', progress: 100});
                        notify.close();

                        swal({
                            icon: 'success',
                            title: 'done posting request '+index+1,
                            text: 'Request '+(index+1)+' posted successfully ',
                        })

                    },
                    error: function (jqXHR, textStatus, error) {

                        reject(error);

                        //vmDeposits.upload_loader = '';
                        //console.log(" got response "+JSON.stringify());
                        swal({
                            icon: 'error',
                            title: 'Error posting request '+index+1,
                            text: 'Something went wrong! please try again',
                        })

                    }
                });

            });

        });
    }

    function ValidateEmail(mail)
    {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(myForm.emailAddr.value))
        {
            return (true)
        }
        return (false)
    }


});