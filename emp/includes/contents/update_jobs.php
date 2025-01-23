<div class="update-modal" id="updateJob<?php echo htmlspecialchars($job['job_id']) ?>">
    <div class="update-content" style="height: fit-content !important; padding-bottom: 20px; max-width: 600px" id="addJobBg">
        <div class="update-header">
            <h4>UPDATE AN JOB</h4>
        </div>
        <div class="update-body">
            <form action="handlers/jobs/update_job.php" method="POST" class="addForm" onsubmit="updateConfirm(event)">
                <input type="hidden" name="update_job_id" value="<?php echo htmlspecialchars($job['job_id']) ?>">
                <div class="update-row">
                    <div class="update-input">
                        <p>Job Position:</p>
                        <input type="text" name="update_jobPosition" value="<?php echo htmlspecialchars($job['job_position']) ?>" required autocomplete="off">
                    </div>

                    <div class="update-input">
                        <p>Plantilla Item:</p>
                        <input type="number" name="update_plantillaItem" value="<?php echo htmlspecialchars($job['plantilla_item']) ?>" required autocomplete="off">
                    </div>
                </div>

                <div class="update-row">
                    <div class="update-input">
                        <p>Pay Grade:</p>
                        <input type="number" name="update_payGrade" value="<?php echo htmlspecialchars($job['pay_grade']) ?>" required autocomplete="off">
                    </div>

                    <div class="update-input">
                        <p>Monthly Salary:</p>
                        <input type="text" name="update_monthlySalary" value="<?php echo htmlspecialchars($job['monthly_salary']) ?>" required autocomplete="off">
                    </div>
                </div>

                <div class="update-row">
                    <div class="update-input">
                        <p>Education:</p>
                        <select name="update_education" required autocomplete="off">
                            <option value="Elementary" <?php echo ($job['education'] == 'Elementary') ? 'selected' : ''; ?>>Elementary</option>
                            <option value="High School" <?php echo ($job['education'] == 'High School') ? 'selected' : ''; ?>>High School</option>
                            <option value="Vocational" <?php echo ($job['education'] == 'Vocational') ? 'selected' : ''; ?>>Vocational</option>
                            <option value="College" <?php echo ($job['education'] == 'College') ? 'selected' : ''; ?>>College</option>
                            <option value="Postgraduate" <?php echo ($job['education'] == 'Postgraduate') ? 'selected' : ''; ?>>Postgraduate</option>
                        </select>
                    </div>


                    <div class="update-input">
                        <p>Training:</p>
                        <input type="text" name="update_training" value="<?php echo htmlspecialchars($job['training']) ?>" required autocomplete="off">
                    </div>
                </div>

                <div class="update-row">
                    <div class="update-input">
                        <p>Experience:</p>
                        <select name="update_experience" required autocomplete="off">
                            <option value="Less than 1 year" <?php echo ($job['experience'] == 'Less than 1 year') ? 'selected' : ''; ?>>Less than 1 year</option>
                            <option value="1 year" <?php echo ($job['experience'] == '1 year') ? 'selected' : ''; ?>>1 year</option>
                            <option value="2 years" <?php echo ($job['experience'] == '2 years') ? 'selected' : ''; ?>>2 years</option>
                            <option value="3 years" <?php echo ($job['experience'] == '3 years') ? 'selected' : ''; ?>>3 years</option>
                            <option value="4 years" <?php echo ($job['experience'] == '4 years') ? 'selected' : ''; ?>>4 years</option>
                            <option value="5 years" <?php echo ($job['experience'] == '5 years') ? 'selected' : ''; ?>>5 years</option>
                            <option value="6-9 years" <?php echo ($job['experience'] == '6-9 years') ? 'selected' : ''; ?>>6-9 years</option>
                            <option value="10 years" <?php echo ($job['experience'] == '10 years') ? 'selected' : ''; ?>>10 years</option>
                            <option value="15 years" <?php echo ($job['experience'] == '15 years') ? 'selected' : ''; ?>>15 years</option>
                            <option value="20 years" <?php echo ($job['experience'] == '20 years') ? 'selected' : ''; ?>>20 years</option>
                        </select>
                    </div>
                    <div class="update-input">
                        <p>Eligibility:</p>
                        <input type="text" name="update_eligibility" value="<?php echo htmlspecialchars($job['eligibility']) ?>" required autocomplete="off">
                    </div>
                </div>
                <div class="update-input">
                    <p>Competency:</p>
                    <input type="text" name="update_competency" value="<?php echo htmlspecialchars($job['competency']) ?>" required autocomplete="off">
                </div>
                <div class="update-row">
                    <div class="update-input">
                        <label for="job_skills">Skills Needed:</label>
                        <select id="job_skills" name="job_skills" required autocomplete="off">
                            <option value="Water Treatment" <?php echo ($job['job_skills'] == 'Water Treatment') ? 'selected' : ''; ?>>Water Treatment</option>
                            <option value="Water Distribution" <?php echo ($job['job_skills'] == 'Water Distribution') ? 'selected' : ''; ?>>Water Distribution</option>
                            <option value="Customer Service" <?php echo ($job['job_skills'] == 'Customer Service') ? 'selected' : ''; ?>>Customer Service</option>
                            <option value="Maintenance and Repair" <?php echo ($job['job_skills'] == 'Maintenance and Repair') ? 'selected' : ''; ?>>Maintenance and Repair</option>
                            <option value="Water Conservation" <?php echo ($job['job_skills'] == 'Water Conservation') ? 'selected' : ''; ?>>Water Conservation</option>
                            <option value="Quality Control" <?php echo ($job['job_skills'] == 'Quality Control') ? 'selected' : ''; ?>>Quality Control</option>
                            <option value="System Design and Engineering" <?php echo ($job['job_skills'] == 'System Design and Engineering') ? 'selected' : ''; ?>>System Design and Engineering</option>
                            <option value="Regulatory Compliance" <?php echo ($job['job_skills'] == 'Regulatory Compliance') ? 'selected' : ''; ?>>Regulatory Compliance</option>
                            <option value="Project Management" <?php echo ($job['job_skills'] == 'Project Management') ? 'selected' : ''; ?>>Project Management</option>
                            <option value="Financial Management" <?php echo ($job['job_skills'] == 'Financial Management') ? 'selected' : ''; ?>>Financial Management</option>
                            <option value="Supply Chain Management" <?php echo ($job['job_skills'] == 'Supply Chain Management') ? 'selected' : ''; ?>>Supply Chain Management</option>
                            <option value="Risk Management" <?php echo ($job['job_skills'] == 'Risk Management') ? 'selected' : ''; ?>>Risk Management</option>
                            <option value="Infrastructure Development" <?php echo ($job['job_skills'] == 'Infrastructure Development') ? 'selected' : ''; ?>>Infrastructure Development</option>
                            <option value="Staff Training and Development" <?php echo ($job['job_skills'] == 'Staff Training and Development') ? 'selected' : ''; ?>>Staff Training and Development</option>
                            <option value="Emergency Response" <?php echo ($job['job_skills'] == 'Emergency Response') ? 'selected' : ''; ?>>Emergency Response</option>
                            <option value="Technology Implementation" <?php echo ($job['job_skills'] == 'Technology Implementation') ? 'selected' : ''; ?>>Technology Implementation</option>
                            <option value="Data Analysis and Reporting" <?php echo ($job['job_skills'] == 'Data Analysis and Reporting') ? 'selected' : ''; ?>>Data Analysis and Reporting</option>
                            <option value="Community Engagement" <?php echo ($job['job_skills'] == 'Community Engagement') ? 'selected' : ''; ?>>Community Engagement</option>
                            <option value="Environmental Impact Assessment" <?php echo ($job['job_skills'] == 'Environmental Impact Assessment') ? 'selected' : ''; ?>>Environmental Impact Assessment</option>
                            <option value="Water Resource Management" <?php echo ($job['job_skills'] == 'Water Resource Management') ? 'selected' : ''; ?>>Water Resource Management</option>
                            <option value="Legal and Policy Knowledge" <?php echo ($job['job_skills'] == 'Legal and Policy Knowledge') ? 'selected' : ''; ?>>Legal and Policy Knowledge</option>
                            <option value="Supply and Demand Forecasting" <?php echo ($job['job_skills'] == 'Supply and Demand Forecasting') ? 'selected' : ''; ?>>Supply and Demand Forecasting</option>
                            <option value="Infrastructure Asset Management" <?php echo ($job['job_skills'] == 'Infrastructure Asset Management') ? 'selected' : ''; ?>>Infrastructure Asset Management</option>
                            <option value="Water Sampling and Testing" <?php echo ($job['job_skills'] == 'Water Sampling and Testing') ? 'selected' : ''; ?>>Water Sampling and Testing</option>
                            <option value="Customer Billing and Account Management" <?php echo ($job['job_skills'] == 'Customer Billing and Account Management') ? 'selected' : ''; ?>>Customer Billing and Account Management</option>
                            <option value="Operational Efficiency" <?php echo ($job['job_skills'] == 'Operational Efficiency') ? 'selected' : ''; ?>>Operational Efficiency</option>
                            <option value="Health and Safety Standards" <?php echo ($job['job_skills'] == 'Health and Safety Standards') ? 'selected' : ''; ?>>Health and Safety Standards</option>
                            <option value="Water Loss Control" <?php echo ($job['job_skills'] == 'Water Loss Control') ? 'selected' : ''; ?>>Water Loss Control</option>
                        </select>
                    </div>
                    <div class="update-input">
                        <label for="job_type">Type of job:</label>
                        <select id="job_type" name="job_type" required autocomplete="off " style="font-size: 12px !important; padding: 5px">
                            <option value="Full-time" <?php echo ($job['job_type'] === 'Full-time') ? 'selected' : ''; ?>>Full-time</option>
                            <option value="Part-time" <?php echo ($job['job_type'] === 'Part-time') ? 'selected' : ''; ?>>Part-time</option>
                            <option value="Contract" <?php echo ($job['job_type'] === 'Contract') ? 'selected' : ''; ?>>Contract</option>
                            <option value="Internship" <?php echo ($job['job_type'] === 'Internship') ? 'selected' : ''; ?>>Internship</option>
                        </select>
                    </div>
                </div>
                <div class="update-row">
                    <div class="update-input">
                        <p>Place:</p>
                        <input type="text" name="update_place" value="<?php echo htmlspecialchars($job['place']) ?>" required autocomplete="off">
                    </div>

                    <div class="update-input">
                        <p>Open Position:</p>
                        <input type="number" name="update_openPosition" value="<?php echo htmlspecialchars($job['open_position']) ?>" required autocomplete="off">
                    </div>
                </div>
                <div class="update-input">
                    <p>Status:</p>
                    <select name="update_status" required autocomplete="off" style="font-size: 12px !important; padding: 5px">
                        <option value="" disabled>Select a status</option>
                        <option value="Open" <?php echo ($job['status'] === 'Open') ? 'selected' : ''; ?>>Open</option>
                        <option value="Closed" <?php echo ($job['status'] === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>
                <div class="update-input">
                    <label for="place">Job Description:</label>
                    <textarea type="text" id="jobDescription" style="padding-left: 5px;" rows="3" name="jobDescription" placeholder="Input job description" autocomplete="off"><?php echo htmlspecialchars($job['job_description']) ?></textarea>
                </div>

                <div class="update-buttons">
                    <input type="submit" name="add_account" value="UPDATE JOB" style="background-color: blue">
                    <button type="button" style="background-color: red" onclick="closeUpdateModal(<?php echo htmlspecialchars($job['job_id']) ?>)">CANCEL</button>
                </div>
            </form>
        </div>
    </div>
</div>