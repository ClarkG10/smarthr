<div class="add-modal" id="addJobModal">
    <div class="add-content" id="addJobBg" style="height: fit-content !important;">
        <div class="add-header">
            <h4>ADD AN JOB</h4>
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
                        <input type="text" id="education" name="education" required autocomplete="off">
                    </div>

                    <div class="add-input">
                        <label for="training">Training:</label>
                        <input type="text" id="training" name="training" required autocomplete="off">
                    </div>
                </div>

                <div class="add-row">
                    <div class="add-input">
                        <label for="experience">Experience:</label>
                        <input type="text" id="experience" name="experience" required autocomplete="off">
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