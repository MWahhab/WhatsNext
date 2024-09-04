<div id="editt-form"
     class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700 fixed inset-0 m-auto"
     style="z-index: 2000; width: 90%; max-width: 500px; display: none">

    <div class="relative flex items-center mb-4">
        <h5 class="text-xl font-medium text-gray-500 dark:text-gray-400 ">Edit Task Details Here!</h5>
        <svg class="w-6 h-6 text-gray-800 dark:text-white" style="margin-left: 208px; cursor:pointer;"
             aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16"
             onclick="alterEditFormVisibility()">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
        </svg>
    </div>

    <div class="flow-root">


        <ul role="list" class="space-y-5 my-7">

            <x-timepicker id="edit-form-time" name="time_due"/>

            <div>
                <label for="edit-form-task"
                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Task</label>
                <input type="text" id="edit-form-task"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="Enter Task Name" name="task" required/>
            </div>

            <label for="edit-form-description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
            <textarea id="edit-form-description" name="description" rows="4"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      placeholder="Enter Task Description" required></textarea>

            <button id="task-edit-submit" type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-200
                dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5
                py-2.5 inline-flex justify-center w-full text-center" onclick="alterTask()">
                Edit Task
            </button>

            <div id="edit-form-obj-id" style="display: none"></div>
            <div id="edit-form-date" style="display: none"></div>
            <div id="edit-form-repeat" style="display: none"></div>
            <div id="edit-form-time-due" style="display: none"></div>
            <div id="edit-form-single" style="display: none"></div>

        </ul>

        <p class="block mt-12 mb-4 text-lg font-medium text-gray-900 dark:text-white text-center">OR</p>

        <button type="button"
                class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-700 dark:hover:bg-red-800 dark:focus:ring-red-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center"
                onclick="deleteTask()"
        >
            Delete Task
        </button>

    </div>
</div>


<script>
    function alterTask() {
        document.getElementById("editt-form").style.display = "none";
        document.getElementById("repeat-tasks-display").style.display = "none";

        let objId      = document.getElementById("edit-form-obj-id").value;
        let newTime    = document.getElementById("edit-form-time").value;
        let newTask    = document.getElementById("edit-form-task").value;
        let newDesc    = document.getElementById("edit-form-description").value;
        let taskDate   = document.getElementById("edit-form-date").value;
        let repeatTask = document.getElementById("edit-form-repeat").value == 1;
        let singleTask = document.getElementById("edit-form-single").value == 1;

        axios.put("objective/" + objId,
            {
                newTime   : newTime,
                newTask   : newTask,
                newDesc   : newDesc,
                taskDate  : taskDate,
                repeatTask: repeatTask,
                singleTask: singleTask
            }
        ).then(response => {
            console.log("deletion response: ", response.date);

            reflectEditedTask(objId, newTime, newTask, newDesc, taskDate, repeatTask, singleTask);
        }).catch(err => {
            console.log("error altering task: ", err);
        })
    }

    function reflectEditedTask(objId, newTime, newTask, newDesc, taskDate, repeatTask, singleTask) {
        console.log("obj id: ", objId);
        console.log("newtime is: ", newTime);
        console.log("newtask is: ", newTask);
        console.log("newdesc is: ", newDesc);
        console.log("taskdate is: ", taskDate);
        console.log("repeattask is: ", repeatTask);
        console.log("single is: ", singleTask);
        if(singleTask == true){
            let taskEle = document.getElementById(`${objId}-${taskDate}-task`);
            let descEle = document.getElementById(`${objId}-${taskDate}-description`);
            let timeEle = document.getElementById(`${objId}-${taskDate}-time`);

            taskEle.innerText = newTask;
            descEle.innerText = newDesc;
            timeEle.innerText = newTime;

            taskEle.style.color = "#F97316";
            descEle.style.color = "#F97316";
            timeEle.style.color = "#F97316";

            return;
        }
        let repeatTaskEle = document.getElementById(`repeating-task-${objId}-task`);
        let repeatTimeEle = document.getElementById(`repeating-task-${objId}-time-due`);
        let repeatDescEle = document.getElementById(`repeating-task-${objId}-description`);

        repeatTaskEle.innerText = newTask;
        repeatTimeEle.innerText = newTime;
        repeatDescEle.innerText = newDesc;

        repeatTaskEle.style.color = "#F97316";
        repeatTimeEle.style.color = "#F97316";
        repeatDescEle.style.color = "#F97316";

        let $allTasks        = document.querySelectorAll(`.task-${objId}`);
        let $allDescriptions = document.querySelectorAll(`.description-${objId}`);
        let $allTimes        = document.querySelectorAll(`.time-${objId}`);

        $allTasks.forEach(taskEle => {
            if(document.getElementById(`${objId}-${taskDate}-unique`)){
                return; //for some reason i cant use continue here. but this works to skip this iteration
            }

            taskEle.innerText = newTask;
            taskEle.style.color = "#F97316";
        });

        $allDescriptions.forEach(taskEle => {
            taskEle.innerText = newDesc;
            taskEle.style.color = "#F97316";
        });

        $allTimes.forEach(taskEle => {
            taskEle.innerText = newTime;
            taskEle.style.color = "#F97316";
        });
    }

    function deleteTask() {
        alterEditFormVisibility();

        let objId      = document.getElementById("edit-form-obj-id").value;
        let taskDate   = document.getElementById("edit-form-date").value;
        let repeatTask = document.getElementById("edit-form-repeat").value == 1;
        let singleTask = document.getElementById("edit-form-single").value == 1;
        let time       = document.getElementById("edit-form-time-due").value;

        axios.delete("objective/" + document.getElementById("edit-form-obj-id").value, {
            params: {
                repeatTask: repeatTask,
                taskDate  : taskDate,
                singleTask: singleTask
            }
        })
            .then(response => {
                console.log("deletion response: ", response.date);

                reflectDeletedTask(taskDate, time, singleTask, objId);
            })
            .catch(err => {
                console.log("error deleting task: ", err);
            })
    }

    function reflectDeletedTask(date, time, singleTask, objId) {
        let tasksToDelete = [document.getElementById(date + "-" + time)];

        if(!singleTask) {
            tasksToDelete = document.querySelectorAll(`.listing-${objId}`);
            document.getElementById(`repeating-task-${objId}-${time}`).remove();

            if(document.getElementById("repeat-tasks-list").childElementCount == 1){
                document.getElementById("repeat-tasks-display").innerHTML = `
            <div class="flex-1 min-w-0 ms-4">
                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                No Repeating Tasks Set! :)
                            </p>
                        </div>
            `;

            }
        }

        tasksToDelete.forEach(taskToDelete => {
            console.log("task being deleted: ", taskToDelete);

            if(taskToDelete.parentElement.childElementCount == 1){
                let dayCardEle = taskToDelete.parentElement.parentElement.parentElement.parentElement;
                console.log("day card ele: ", dayCardEle);

                dayCardEle.innerHTML = `
                        <div id="${date}-taskless-view" class="flex-1 min-w-0 ms-4">
                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                No Chores For This Day! :)
                            </p>
                        </div></div>
                        `;

                return;
            }


            taskToDelete.remove();
        })

    }

    function alterEditFormVisibility(event = null) {
        let editForm = document.getElementById("editt-form");

        if(editForm.style.display == "none"){
            console.log("task listing elee: ", event.target.parentElement);

            let buttonDiv = event.target.parentElement;

            let taskEle        = buttonDiv.nextElementSibling.children[0];
            let descriptionEle = buttonDiv.nextElementSibling.children[1];

            let idEle     = buttonDiv.nextElementSibling.nextElementSibling.nextElementSibling;
            let repeatEle = idEle.nextElementSibling.nextElementSibling;
            let singleEle = repeatEle.nextElementSibling.nextElementSibling

            document.getElementById("edit-form-task").value        = taskEle.innerText;
            document.getElementById("edit-form-obj-id").value      = idEle.innerText;
            document.getElementById("edit-form-description").value = descriptionEle.innerText;
            document.getElementById("edit-form-date").value        = idEle.nextElementSibling.innerText;
            document.getElementById("edit-form-repeat").value      = repeatEle.innerText;
            document.getElementById("edit-form-time-due").value    = repeatEle.nextElementSibling.innerText;
            document.getElementById("edit-form-time").value        = repeatEle.nextElementSibling.innerText;
            document.getElementById("edit-form-single").value      = singleEle.innerText;

            editForm.style.display = "block";

            if(document.getElementById("creation-form").style.display == "block") {
                document.getElementById("creation-form").style.display = "none";
            }

            let repeatingTaskDisplay = document.getElementById("repeat-tasks-display");
            repeatingTaskDisplay.style.display = "none";

            return;
        }

        editForm.style.display = "none";
    }

</script>
