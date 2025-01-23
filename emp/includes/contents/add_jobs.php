<div class="add-modal" id="addJobModal">
    <div class="add-content" id="addJobBg" style="height: fit-content !important; max-width: 500px">
        <div class="add-header">
            <h4>ADD A JOB</h4>
        </div>
        <div class="add-body">
            <form action="handlers/jobs/add_job.php" method="POST" class="addForm">
                <div class="add-row">
                    <div class="add-input">
                        <label for="jobPosition">Job Position:</label>
                        <input type="text" id="jobPosition" name="jobPosition" required autocomplete="off">
                    </div>

                    <div class="add-input">
                        <label for="plantillaItem">Plantilla Item:</label>
                        <input type="number" id="plantillaItem" name="plantillaItem" required autocomplete="off">
                    </div>
                </div>

                <div class="add-row">
                    <div class="add-input">
                        <label for="payGrade">Pay Grade:</label>
                        <input type="number" id="payGrade" name="payGrade" required autocomplete="off">
                    </div>

                    <div class="add-input">
                        <label for="monthlySalary">Monthly Salary:</label>
                        <input type="text" id="monthlySalary" name="monthlySalary" required autocomplete="off">
                    </div>
                </div>

                <div class="add-row">
                    <div class="add-input">
                        <label for="education">Education:</label>
                        <select id="education" name="education" required autocomplete="off">
                            <option value="None Required">None Required</option>
                            <option value="Elementary">Elementary</option>
                            <option value="High School">High School</option>
                            <option value=" Vocational">Vocational</option>
                            <option value=" College">College</option>
                            <option value=" Postgraduate">Postgraduate</option>
                        </select>
                    </div>


                    <div class=" add-input">
                        <label for="training">Training:</label>
                        <input type="text" id="training" name="training" required autocomplete="off">
                    </div>
                </div>

                <div class="add-row">
                    <div class="add-input">
                        <label for="experience">Experience:</label>
                        <select id="experience" name="experience" required autocomplete="off">
                            <option value="Less than 1 year">Less than 1 year</option>
                            <option value="1 year">1 year</option>
                            <option value="2 years">2 years</option>
                            <option value="3 years">3 years</option>
                            <option value="4 years">4 years</option>
                            <option value="5 years">5 years</option>
                            <option value="6-9 years">6-9 years</option>
                            <option value="10 years">10 years</option>
                            <option value="15 years">15 years</option>
                            <option value="20 years">20 years</option>
                        </select>
                    </div>


                    <div class="add-input">
                        <label for="eligibility">Eligibility:</label>
                        <input type="text" id="eligibility" name="eligibility" required autocomplete="off">
                    </div>
                </div>
                <div class="add-input">
                    <label for="competency">Competency:</label>
                    <input type="text" id="competency" name="competency" required autocomplete="off">
                </div>
                <div class="add-row">
                    <div class="add-input">
                        <label for="job_skills">Skills Needed:</label>
                        <select id="job_skills" name="job_skills" required autocomplete="off">
                            <option value="Water Treatment">Water Treatment</option>
                            <option value="Water Distribution">Water Distribution</option>
                            <option value="Customer Service">Customer Service</option>
                            <option value="Maintenance and Repair">Maintenance and Repair</option>
                            <option value="Water Conservation">Water Conservation</option>
                            <option value="Quality Control">Quality Control</option>
                            <option value="System Design and Engineering">System Design and Engineering</option>
                            <option value="Regulatory Compliance">Regulatory Compliance</option>
                            <option value="Project Management">Project Management</option>
                            <option value="Financial Management">Financial Management</option>
                            <option value="Supply Chain Management">Supply Chain Management</option>
                            <option value="Risk Management">Risk Management</option>
                            <option value="Infrastructure Development">Infrastructure Development</option>
                            <option value="Staff Training and Development">Staff Training and Development</option>
                            <option value="Emergency Response">Emergency Response</option>
                            <option value="Technology Implementation">Technology Implementation</option>
                            <option value="Data Analysis and Reporting">Data Analysis and Reporting</option>
                            <option value="Community Engagement">Community Engagement</option>
                            <option value="Environmental Impact Assessment">Environmental Impact Assessment</option>
                            <option value="Water Resource Management">Water Resource Management</option>
                            <option value="Legal and Policy Knowledge">Legal and Policy Knowledge</option>
                            <option value="Supply and Demand Forecasting">Supply and Demand Forecasting</option>
                            <option value="Infrastructure Asset Management">Infrastructure Asset Management</option>
                            <option value="Water Sampling and Testing">Water Sampling and Testing</option>
                            <option value="Customer Billing and Account Management">Customer Billing and Account Management</option>
                            <option value="Operational Efficiency">Operational Efficiency</option>
                            <option value="Health and Safety Standards">Health and Safety Standards</option>
                            <option value="Water Loss Control">Water Loss Control</option>
                        </select>
                    </div>
                    <div class="add-input">
                        <label for="job_type">Type of job:</label>
                        <select id="job_type" name="job_type" required autocomplete="off" style="font-size: 12px !important; padding: 5px">
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Internship">Internship</option>
                        </select>
                    </div>
                </div>
                <div class="add-row">
                    <div class="add-input">
                        <label for="place">Place:</label>
                        <input type="text" id="place" name="place" required autocomplete="off">
                    </div>

                    <div class="add-input">
                        <label for="openPosition">Open Position:</label>
                        <input type="number" id="openPosition" name="openPosition" required autocomplete="off">
                    </div>
                </div>

                <div class="add-input">
                    <label for="place">Job Description:</label>
                    <textarea type="text" style="padding-left: 5px;" id="jobDescription" rows="5" name="jobDescription" placeholder="Input job description" required autocomplete="off"></textarea>
                </div>

                <div class="add-buttons" style="margin-bottom: 10px;">
                    <input type="submit" name="add_account" value="ADD JOB" style="background-color: blue">
                    <button type="button" style="background-color: red" onclick="closeAddJobModal()">CANCEL</button>
                </div>
            </form>
        </div>
    </div>
</div>