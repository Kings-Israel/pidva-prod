<ul class="nav nav-list"><?php


                            $urlparfull = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

                            $urlpar = chop($urlparfull, ".php");

                            if (in_array('VIEW_DASHBOARD', $roledata) || in_array('SUPER_USER', $roledata)) {
                            ?>
        <li class="<?php if ($urlpar == 'dashboard') {
                                    echo "active";
                                } ?>">
            <a href="/html/dashboard/dashboard.php">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span> </a>

            <b class="arrow"></b>
        </li>
    <?php

                            }

                            if (in_array('VIEW_CLIENTS', $roledata)) {

    ?>

        <!--	<li class="<?php if ($urlpar == 'companyfullview' || $urlpar == 'companysearch' || $urlpar == 'individualsearch' || $urlpar == 'individualfullview') {
                                    echo "active";
                                } ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-user"></i>
							<span class="menu-text">
								Reports					</span>

							<b class="arrow fa fa-angle-down"></b>						</a>

						<b class="arrow"></b>

						<ul class="submenu">
                          <li class="<?php if ($urlpar == 'companysearch') {
                                            echo "active";
                                        } ?>">
								<a href="/html/reportdata/companysearch.php">
									<i class="menu-icon fa fa-caret-right"></i>
								Company	</a>

								<b class="arrow"></b>							</li>

							
                                 <li class="">
								<a href="/html/reportdata/individualsearch.php">
									<i class="menu-icon fa fa-caret-right"></i>
								Individual	</a>

								<b class="arrow"></b>							</li>


					</ul>
					</li>-->
    <?php
                            }

                            if (in_array('VIEW_MODULES', $roledata)) {

    ?>
        <li class="<?php if ($urlpar == 'awards' || $urlpar == 'levels' || $urlpar == 'faculty' || $urlpar == 'specialization' || $urlpar == 'institution' || $urlpar == 'course' || $urlpar == 'educationdata' || $urlpar == 'viewinstitution' || $urlpar == 'viewinstitutionplans' || $urlpar == 'companyregcheck' || $urlpar == 'creditcheck' || $urlpar == 'customerrefcheck' || $urlpar == 'globalwatchlist' || $urlpar == 'proffessionalmembership' || $urlpar == 'companymodules' || $urlpar == 'companydataentry' || $urlpar == 'residency' || $urlpar == 'shareholdingcheck' || $urlpar == 'identitycheck' || $urlpar == 'passportcheck' || $urlpar == 'drivinglicensecheck' || $urlpar == 'educationcheck' || $urlpar == 'socialmedia' || $urlpar == 'individualmodules' || $urlpar == 'individualdataentry' || $urlpar == 'watchlistindividualcheck' || $urlpar == 'criminalcheck' || $urlpar == 'psvlicensecheck' || $urlpar == 'proffmemberverificationcheck' || $urlpar == 'taxcompliancecheck' || $urlpar == 'fingerprintcheck' || $urlpar == 'socialmediacheck' || $urlpar == 'residencycheck' || $urlpar == 'employementcheck' || $urlpar == 'watchlistcompanycheck' || $urlpar == 'profflicenseverificationcheck' || $urlpar == 'businesslicensecheck' || $urlpar == 'sitevisitcheck') {
                                    echo "active";
                                } ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-desktop"></i>
                <span class="menu-text">
                    Data Entry </span>

                <b class="arrow fa fa-angle-down"></b> </a>

            <b class="arrow"></b>
            <?php

                                if (in_array('VIEW_MODULES_CONFIGURATION', $roledata)) {

            ?>

                <!--busines license check-->


                <ul class="submenu">


                    <li class="<?php if ($urlpar == 'companymodules') {
                                        echo "active";
                                    } ?>">
                        <a href="/html/company/companymodules.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Company</a>
                        <b class="arrow"></b>
                    </li>


                    <li class="">
                        <a href="/html/filesmanager/vehicle_data.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            NTSA
                        </a>
                        <b class="arrow"></b>
                    </li>

                    <li class="<?php if ($urlpar == 'individualmodules') {
                                        echo "active";
                                    } ?>">
                        <a href="/html/individual/individualmodules.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Individual</a>

                        <b class="arrow"></b>
                    </li>


                    <?php

                                    if (in_array('VIEW_MODULES_DATA', $roledata)) {

                    ?>

                        <!--       <li class="">
                              <a href="/html/education/educationdata.php">
                                  <i class="menu-icon fa fa-caret-right"></i>
                              Education Data</a>

                              <b class="arrow"></b>							</li>-->

                    <?php
                                    }
                    ?>


                </ul>


                <ul class="submenu">
                    <li class="<?php if ($urlpar == 'awards' || $urlpar == 'levels' || $urlpar == 'faculty' || $urlpar == 'specialization' || $urlpar == 'institution' || $urlpar == 'course' || $urlpar == 'educationdata' || $urlpar == 'viewinstitution' || $urlpar == 'viewinstitutionplans' || $urlpar == 'viewinstitutioncourses' || $urlpar == 'addinstitutioncourse' || $urlpar == 'addinstitutionplans') {
                                        echo "active";
                                    } ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>

                            Education
                            <b class="arrow fa fa-angle-down"></b> </a>

                        <b class="arrow"></b>

                        <ul class="submenu">
                            <li class="">
                                <a href="/html/education/awards.php">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Awards </a>

                                <b class="arrow"></b>
                            </li>
                            <li class="">
                                <a href="/html/education/levels.php">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Levels </a>

                                <b class="arrow"></b>
                            </li>
                            <li class="">
                                <a href="/html/filesmanager/faculty.php">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Faculty </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="">
                                <a href="/html/filesmanager/course.php">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Courses </a>

                                <b class="arrow"></b>
                            </li>
                            <li class="">
                                <a href="/html/filesmanager/specialization.php">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Specializations </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="">
                                <a href="/html/filesmanager/institution.php">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Institutions </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="">
                                <a href="/html/education/educationdata.php">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Education Data </a>

                                <b class="arrow"></b>
                            </li>

                            <?php

                                    if (in_array('VIEW_MODULES_DATA', $roledata)) {

                            ?>

                                <!--       <li class="">
                                      <a href="/html/education/educationdata.php">
                                          <i class="menu-icon fa fa-caret-right"></i>
                                      Education Data</a>

                                      <b class="arrow"></b>							</li>-->

                            <?php
                                    }
                            ?>

                        </ul>
                    </li>


                </ul>
            <?php
                                }
            ?>
        </li>
    <?php
                            }
                            if (in_array('VIEW_CLIENTS', $roledata)) {

    ?>

        <li class="<?php if ($urlpar == 'companys' || $urlpar == 'clientsusers') {
                                    echo "active";
                                } ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-user"></i>
                <span class="menu-text">
                    Peleza Clients </span>

                <b class="arrow fa fa-angle-down"></b> </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <li class="">
                    <a href="/html/clients/companys.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Companys </a>

                    <b class="arrow"></b>
                </li>


                <li class="">
                    <a href="/html/clients/clientsusers.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Clients </a>

                    <b class="arrow"></b>
                </li>


            </ul>
        </li>
    <?php
                            }
                            if (in_array('VIEW_SEARCH', $roledata)) {

    ?>
        <li class="<?php if ($urlpar == 'ed_searches' || $urlpar == 'ed_requests') {
                                    echo "active";
                                } ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-search"></i>
                <span class="menu-text">
                    Peleza Searches </span>

                <b class="arrow fa fa-angle-down"></b> </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <li class="">
                    <a href="/html/searches/ed_searches.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Edchecks</a>

                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a href="/html/searches/idrequests1.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        IdChecks</a>

                    <b class="arrow"></b>
                </li>

                <?php

                                if (in_array('VIEW_MANUAL_SEARCH_REQUESTS', $roledata)) {

                ?>
                    <li class="">
                        <a href="/html/searches/ed_requests.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Ed Requests</a>

                        <b class="arrow"></b>
                    </li>
                <?php
                                }
                ?>
                <li class="">
                    <a href="/html/searches/psmt_requests.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Psmt Requests</a>

                    <b class="arrow"></b>
                </li>


            </ul>
        </li>
    <?php
                            }
                            if (in_array('VIEW_PAYMENTS', $roledata)) {

    ?>

        <li class="<?php if ($urlpar == 'education' || $urlpar == 'psmt_requests_quotes' || $urlpar == 'psmt') {
                                    echo "active";
                                } ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-money"></i>
                <span class="menu-text">
                    Payments </span>

                <b class="arrow fa fa-angle-down"></b> </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <li class="">
                    <a href="/html/payments/education.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        EdCheck Payments</a>

                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a href="/html/payments/psmt.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Psmt Payments</a>

                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="/html/payments/psmt_requests_quotes.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Psmt Quotes</a>

                    <b class="arrow"></b>
                </li>
            </ul>
        </li>
    <?php
                            }
                            if (in_array('VIEW_CONFIGURATION', $roledata)) {

    ?>
        <li class="<?php if ($urlpar == 'modules' || $urlpar == 'currency' || $urlpar == 'plans' || $urlpar == 'industry' || $urlpar == 'countries' || $urlpar == 'dataset') {
                                    echo "active";
                                } ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-pencil-square-o"></i>
                <span class="menu-text"> Configurations </span>

                <b class="arrow fa fa-angle-down"></b> </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <li class="">
                    <a href="/html/configurations/modules.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Modules </a>

                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="/html/configurations/currency.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Currency </a>

                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="/html/configurations/plans.php">
                        <i class="menu-icon fa fa-caret-right"></i> EdCheck Plans </a>

                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="/html/configurations/psmtpackages.php">
                        <i class="menu-icon fa fa-caret-right"></i> Psmt Packages </a>

                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="/html/configurations/countries.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Countries </a>

                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="/html/configurations/industry.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Industries </a>

                    <b class="arrow"></b>
                </li>

                <li class="">
                    <a href="/html/configurations/dataset.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Data Sets </a>

                    <b class="arrow"></b>
                </li>
            </ul>
        </li><?php
                            }
                            if (in_array('VIEW_FILE_MANAGER', $roledata)) {

                ?>
        <li class="<?php if ($urlpar == 'datafiles' || $urlpar == 'educationupload' || $urlpar == 'viewfiledata' || $urlpar == 'datauploader' || $urlpar == 'staginguploader' || $urlpar == 'idupload') {
                                    echo "active";
                                } ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-list-alt"></i>
                <span class="menu-text"> Files Manager </span>

                <b class="arrow fa fa-angle-down"></b> </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <li class="">
                    <a href="/html/filesmanager/datafiles.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Data Files </a>

                    <b class="arrow"></b>
                </li>
                <?php

                                if (in_array('FILE_UPLOADER', $roledata)) {

                ?>
                    <li class="">
                        <a href="/html/filesmanager/educationupload.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Education Upload </a>

                        <b class="arrow"></b>
                    </li>

                    <li class="">
                        <a href="/html/filesmanager/datauploader.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Data Sets Uploader </a>

                        <b class="arrow"></b>
                    </li>

                    <li class="">
                        <a href="/html/filesmanager/idupload.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Id Uploader </a>

                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="/html/filesmanager/student_data.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Education Staging </a>

                        <b class="arrow"></b>
                    </li>
                <?php
                                }
                ?>

            </ul>
        </li>
    <?php
                            }
                            if (in_array('VIEW_USERS', $roledata) || in_array('SUPER_USER', $roledata)) {

    ?>
        <li class="<?php if ($urlpar == 'sysadmin' || $urlpar == 'users' || $urlpar == 'profilemanager' || $urlpar == 'viewprofileroles' || $urlpar == 'assignprofileroles') {
                                    echo "active";
                                } ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-user"></i>
                <span class="menu-text">
                    User Mgt </span>

                <b class="arrow fa fa-angle-down"></b> </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <?php
                                if (in_array('SUPER_USER', $roledata)) {

                ?>
                    <li class="">
                        <a href="/html/user/sysadmin.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Sys Admins </a>

                        <b class="arrow"></b>
                    </li>

                <?php

                                } else {
                ?>

                    <li class="">
                        <a href="/html/user/users.php">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Sys Users</a>

                        <b class="arrow"></b>
                    </li>


                <?php
                                }

                ?>
                <li class="">
                    <a href="/html/user/profilemanager.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Profiles</a>

                    <b class="arrow"></b>
                </li>

            </ul>
        </li>
    <?php
                            }
                            if (in_array('VIEW_MY_PROFILE', $roledata) || in_array('SUPER_USER', $roledata)) {

    ?>
        <?php if ($urlpar == 'profile') {
        ?>
            <li class="active">
            <?php
                                } else {
            ?>
            <li class="">
            <?php
                                }
            ?>
            <a href="/html/user/profile.php">
                <i class="menu-icon fa fa-user"></i>
                <span class="menu-text">My Profile</span> </a>

            <b class="arrow"></b>
            </li><?php
                            }

                    ?>
        <li class="">
            <a href="<?php echo $logoutAction ?>">
                <i class="menu-icon fa fa-eye-slash "></i>
                <span class="menu-text">Log Out</span> </a>

            <b class="arrow"></b>
        </li>

</ul><!-- /.nav-list -->