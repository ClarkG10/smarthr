<div class="view-modal" id="viewModal<?php echo htmlspecialchars($job['job_id']) ?>">
    <div class="view-content" style="height: fit-content;">
        <div class="view-header">
            <h4>JOB DETAILS</h4>
        </div>
        <div class="view-body">
            <div class="dataInfo">
                <strong>Position Title:</strong>
                <h1><?php echo htmlspecialchars($job['job_position']) ?></h1>
            </div>
            <div class="row">
                <div class="dataInfo">
                    <strong>Plantilla Item:</strong>
                    <p><?php echo htmlspecialchars($job['plantilla_item']) ?></p>
                </div>
                <div class="dataInfo">
                    <strong>Pay Grade:</strong>
                    <p><?php echo htmlspecialchars($job['pay_grade']) ?></p>
                </div>
            </div>
            <div class="dataInfo">
                <strong>Monthly Salary:</strong>
                <p><?php echo htmlspecialchars($job['monthly_salary']) ?></p>
            </div>
            <div class="row">
                <div class="dataInfo">
                    <strong>Education:</strong>
                    <p><?php echo htmlspecialchars($job['education']) ?></p>
                </div>
                <div class="dataInfo">
                    <strong>Training:</strong>
                    <p><?php echo htmlspecialchars($job['training']) ?></p>
                </div>
            </div>
            <div class="row">
                <div class="dataInfo">
                    <strong>Experience:</strong>
                    <p><?php echo htmlspecialchars($job['experience']) ?></p>
                </div>
                <div class="dataInfo">
                    <strong>Eligibility:</strong>
                    <p><?php echo htmlspecialchars($job['eligibility']) ?></p>
                </div>
            </div>
            <div class="row">
                <div class="dataInfo">
                    <strong>Competency:</strong>
                    <p><?php echo htmlspecialchars($job['competency']) ?></p>
                </div>
                <div class="dataInfo">
                    <strong>Place of Work:</strong>
                    <p><?php echo htmlspecialchars($job['place']) ?></p>
                </div>
            </div>
            <div class="dataInfo">
                <strong>Job Description:</strong>
                <p><?php echo htmlspecialchars($job['job_description']) ?></p>
            </div>
            <div class="row">
                <div class="dataInfo">
                    <strong>Open Position:</strong>
                    <p><?php echo htmlspecialchars($job['open_position']) ?></p>
                </div>
                <div class="dataInfo-button">
                    <button onclick="closeViewModal(<?php echo htmlspecialchars($job['job_id']) ?>)">CLOSE</button>
                    <a href="http://localhost/smarthr/applicant/apply.php?job_id=<?php echo htmlspecialchars($job['job_id']) ?>"><button>APPLY</button></a>
                </div>
            </div>
        </div>
    </div>
</div>