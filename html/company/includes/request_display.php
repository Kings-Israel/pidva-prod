<?php


$query_getstudent = "SELECT * FROM pel_psmt_request WHERE request_id = " . $request_id . "";
$getstudent = mysqli_query_ported($query_getstudent, $connect) or die(mysqli_error($connect));
$row_getstudent = mysqli_fetch_assoc($getstudent);
$totalRows_getstudent = mysqli_num_rows($getstudent);

?>

<h3 align="left" class=" smaller lighter blue"><strong>SEARCH REF: </strong> <?php echo $row_getstudent['request_ref_number']; ?></h3>

<div>
    <table id="simple-table" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>

                <th>Dataset Name</th>
                <th>Client Name</th>

                <th>Request Package</th>
                <th>Request Date</th>

                <th class="hidden-480">Status</th>

            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <a href="#"><?php echo $row_getstudent['bg_dataset_name']; ?></a>
                </td>
                <td><?php echo $row_getstudent['client_name']; ?></td>

                <td><?php echo $row_getstudent['request_plan']; ?></td>
                <td><?php echo $row_getstudent['request_date']; ?></td>

                <td class="hidden-480"> <?php

                                        if ($row_getstudent['verification_status'] == '44') {
                                        ?>
                        <span class="label label-sm label-warning">In Progress</span>
                    <?php
                                        }
                                        if ($row_getstudent['verification_status'] == '00') {
                    ?>
                        <span class="label label-sm label-purple">New Request</span>
                    <?php
                                        }
                                        if ($row_getstudent['verification_status'] == '11') {
                    ?>
                        <span class="label label-sm label-success">Final</span>
                    <?php
                                        }
                                        if ($row_getstudent['verification_status'] == '22') {
                    ?>
                        <span class="label label-sm label-warning">Not Reviewed</span>
                    <?php
                                        }
                                        if ($row_getstudent['verification_status'] == '33') {
                    ?>
                        <span class="label label-sm label-primary">Interim Data</span>
                    <?php
                                        }
                    ?>
                </td>



            </tr>



        </tbody>
    </table>
    <a href="companydataentry.php?request_id=<?php echo $row_getstudent['request_id']; ?>" role="button" class="green">
        <button class="btn btn-xs btn-primary">
            <i class="ace-icon smaller-80 green"></i>Go Back
        </button></a>
</div>