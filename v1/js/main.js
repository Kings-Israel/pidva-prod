var a = {
    pel_psmt_request:{},
    pel_psmt_employ_data:[],
    pel_individual_id:[],
    pel_individual_fprint_data:[],
    pel_company_registration:[],
    pel_company_license:[],
    pel_company_shares_data:[],
    pel_company_shares_data_comm:[],
    pel_company_credit_data:[],
    pel_credit_data_comments:[],
    pel_company_tax_data:[],
    pel_data_proff_membership:[],
    pel_company_customer_ref:[],
    pel_data_residence:[],
    pel_data_social_media:[],
    pel_company_watchlist_data:[],
    pel_individual_credit_data:[],
    pel_individual_criminal_data:[],
    pel_individual_tax_data:[],
    pel_individual_dl_data:[],
    pel_individual_psv_data:[],
    pel_psmt_edu_data:[],
    pel_individual_gap_data:[],
    pel_individual_watchlist_data:[]
};

var app = new Vue({
    el: '#main',
    data: {

        printing: false,
        report: a,
        request_id: 0
    },
    mounted: function() {

        this.getReport();
    },
    computed: {

        closed: function(){

            var ids = 0;
            var vm = this;

            $.each(vm.report.pel_individual_credit_data,function(k,v){

                if(v.loan_status == "CLOSED") {

                    ids++;
                }

            });

            return ids;
        },
        open: function(){

            var ids = 0;
            var vm = this;

            $.each(vm.report.pel_individual_credit_data,function(k,v){

                if(v.loan_status == "OPEN") {

                    ids++;
                }

            });

            return ids;
        },
        id: function () {

            var ids = {};
            var vm = this;

            $.each(vm.report.pel_individual_id,function(k,v){

                if(v.identity_type === "NATIONAL IDENTITY") {

                    ids = v;
                }

            });

            return ids;
        },
        passport: function () {

            var ids = {};
            var vm = this;

            $.each(vm.report.pel_individual_id,function(k,v){

                if(v.identity_type === "PASSPORT") {

                    ids = v;
                }

            });

            return ids;
        }

    },
    methods: {

        getUniqueID: function(prefix){


            return prefix+"_"+Math.floor(Math.random() * 1000);

        },

        capture: function() {

            var vm = this;
            vm.printing = true;

            var count = 0;
            var payload = [];
            var request = [];

            html2canvas(document.querySelector("#cover")).then(function(canvas) {

                var row = {};
                row.name = "cover_page";
                row.data = canvas.toDataURL("image/png");
                payload.push(row);

                var completed = 0;

                console.log('done with cover page ');

                var x = document.getElementsByClassName("page");

                for (i = 0; i < x.length; i++) {

                    var elemnt = x[i];
                    var id = elemnt.getAttribute("id");

                    if(id === "cover") {

                        continue;

                    } else {

                        count++;

                        console.log('at page '+i+" with ID "+id);

                        var req = html2canvas(document.querySelector("#"+id)).then(function(canvas) {

                            completed++;
                            var row = {};
                            row.name = "Page"+completed+"_id_"+id;
                            row.data = canvas.toDataURL("image/png");
                            payload.push(row);

                            console.log('done with page '+completed);

                        });
                        request.push(req);
                    }
                }

                Promise.all(request).then(function(responses) {

                    //return responses.map(response => {response.json()})
                    console.log(" completed "+completed+" count "+count);

                    var baseURL = window.location.protocol + '//' + window.location.hostname + "/";
                    var ur = baseURL + 'v1/api/Printer.php?type=js';

                    axios.post(ur, payload)
                        .then(function (response) {

                            vm.printing = false;

                            console.log('GOT response '+JSON.stringify(response.data));
                            var dataUrl =response.data.file;
                            //window.location.href = dataUrl;

                            if(!dataUrl) {

                                swal({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong! please try again',
                                });

                                return;
                            }

                            // Construct the 'a' element
                            var link = document.createElement("a");
                            link.download = "report.docx";
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

                            console.log('GOT response error '+JSON.stringify(error))

                        });
                });
            });
        },

        printer: function(){

            var vm = this;
            vm.printing = true;
            return this.capture();

            var data = {
                request_id: this.request_id,
                report: this.report
            };

            var baseURL = window.location.protocol + '//' + window.location.hostname + "/";
            var ur = baseURL + 'v1/api/Printer.php';

            axios.post(ur, data)
                .then(function (response) {

                    vm.printing = false;

                    console.log('GOT response '+JSON.stringify(response.data));
                    var dataUrl =response.data.file;
                    //window.location.href = dataUrl;

                    if(!dataUrl) {

                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong! please try again',
                        });

                        return;
                    }

                    // Construct the 'a' element
                    var link = document.createElement("a");
                    link.download = "report.docx";
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

                    console.log('GOT response error '+JSON.stringify(error))

                });

        },
        hasPhoto: function(d) {

            if(!this.isset(d)) {

                return false;
            }

            var x = 0;

            $.each(d,function(k,v){

                if(v.photo) {

                    x++;
                }

            });

            return x > 0;

            //return false;
        },
        financial: function(data){

            if(!data) {

                return 0;
            }

            if(!isNaN(parseFloat(data)) && isFinite(data)) {

                return new Intl.NumberFormat('en-IN', {maximumSignificantDigits: 3}).format(data);

                return parseFloat(data).toFixed(2)

            }

            return 0;

        },
        getStatus: function(status) {

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
        isset: function(a) {

            if(!a) {

                return false
            }

            if(a == undefined || a === undefined) {

                return false
            }

            if(a == null || a === null) {

                return false;
            }

            if(a.length === 0 ) {

                return false;
            }

            return true;

        },
        getReport: function(){

            var vm = this;

            vm.request_id = document.getElementById('request-id').innerHTML;

            var data = {
                request_id: vm.request_id,
            };

            axios.post('/v1/api/Report.php', data)
                .then(function (response) {

                    //console.log(JSON.stringify(response,undefined,2));

                    vm.report = a;

                    var report = response.data;

                    $.each(report,function (k,v) {

                        $.each(vm.report,function (kk,vv) {

                            if(k == kk ) {

                                vm.report[kk] = v;
                            }
                        })

                    });

                    var elm = document.getElementsByClassName('remove-font');
                    var i;
                    for (i = 0; i < elm.length; i++) {

                        var elem = elm[i].getElementsByTagName("table");

                        if(elem !== undefined && elem.length > 0 ) {

                            console.log("Got "+elem.length+" tables");
                            elem[0].style.removeProperty('font-family');
                        }
                    }

                    //console.log(JSON.stringify(vm.report,undefined,2));

                })
                .catch(function (error) {

                    console.log(error);

                });
        }

    }
})