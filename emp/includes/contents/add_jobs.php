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
                            <option value="Vocational">Vocational</option>
                            <option value="College">College</option>
                            <option value="Postgraduate">Postgraduate</option>
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
                            <option value="None Required">None Required</option>
                            <option value="Less than 1 year">Less than 1 year</option>
                            <option value="1 year">1 year</option>
                            <option value="2 years">2 years</option>
                            <option value="3 years">3 years</option>
                            <option value="4 years">4 years</option>
                            <option value="5 years">5 years</option>
                            <option value="6 years">6 years</option>
                            <option value="7 years">7 years</option>
                            <option value="8 years">8 years</option>
                            <option value="9 years">9 years</option>
                            <option value="10-14 years">10-14 years</option>
                            <option value="15-19 years">15-19 years</option>
                            <option value="20+ years">20+ years</option>
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
                        <input type="text" id="job_skills" name="job_skills" required autocomplete="off">
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