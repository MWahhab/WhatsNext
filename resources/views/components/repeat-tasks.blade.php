<style>
    /* Scrollbar customization for WebKit-based browsers */
    #repeat-tasks-list::-webkit-scrollbar {
        width: 4px; /* Set the width of the scrollbar to be very thin */
        background: transparent; /* Hide scrollbar track by default */
    }

    #repeat-tasks-list::-webkit-scrollbar-track {
        background: transparent; /* Background of the scrollbar track (hidden) */
    }

    #repeat-tasks-list::-webkit-scrollbar-thumb {
        background-color: transparent; /* Hide the scrollbar thumb */
    }

    /* Show scrollbar on hover */
    #repeat-tasks-list:hover::-webkit-scrollbar-track {
        background: #f1f1f1; /* Show track on hover */
    }

    #repeat-tasks-list:hover::-webkit-scrollbar-thumb {
        background-color: #888; /* Show thumb color on hover */
        border-radius: 10px; /* Rounded edges on the thumb */
    }

    /* Scrollbar customization for Firefox */
    #repeat-tasks-list {
        /*scrollbar-width: thin; !* Makes the scrollbar thin *!*/
        /*scrollbar-color: #888 #f1f1f1; !* Thumb color and track color *!*/
    }

    /* General styling for the ul */
    #repeat-tasks-list {
        overflow-y: auto;
        max-height: 160px; /* You can adjust this value */
        background-color: #fff; /* Background color for the list */
        padding: 10px; /* Padding inside the ul */
        border-radius: 10px; /* Rounded corners for the ul */
    }
</style>

<div id="repeat-tasks-display"
     class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700 fixed inset-0 m-auto"
     style="z-index: 2000; width: 90%; max-width: 500px; display: none">

    <div class="relative flex items-center mb-4">
        <h5 class="text-xl font-medium text-gray-500 dark:text-gray-400 mr-2" style="text-decoration: underline">Repeating Tasks</h5>
        <svg class="w-6 h-6 text-gray-800 dark:text-white" style="margin-left: 255px; cursor:pointer;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16" onclick="toggleRepeatTaskDisplayVisbility()">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
        </svg>

    </div>

    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-bold leading-none text-gray-500 dark:text-white">Task</h5>
        <h5 class="text-xl font-bold text-gray-500 dark:text-white">Due</h5>
    </div>

    <!-- Task List -->
    <div class="flow-root">
        <ul id="repeat-tasks-list" role="list" class="divide-y divide-gray-200 dark:divide-gray-700"
        style="overflow-y: auto; max-height: 1160px;">

        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>

    function toggleRepeatTaskDisplayVisbility(){
        let display = document.getElementById("repeat-tasks-display");

        display.style.display = display.style.display == "none" ? "block" : "none";
    }

    function retrieveRepeatTasks(){
        axios.get("{{ route("objective.retrieveRepeatObjectives") }}")
            .then(response => {
            console.log("repeat tasks retrieved: ", response.data);

            displayRepeatTasks(response.data);
        })
            .catch(err => {
                console.log("Error retrieving repeat tasks: ", err);
            })
    }

    function displayRepeatTasks(repeatTasks){
        // if (!repeatingTask["date_due"] || !repeatingTask["time_due"]) {
        //     console.error("Incomplete task data:", repeatingTask);
        // }

        console.log("repeattttt tasls: ", repeatTasks);

        const repeatTaskDisplay = document.getElementById("repeat-tasks-display");

        if(repeatTasks.length < 1){
            repeatTaskDisplay.innerHTML = `
            <div class="flex-1 min-w-0 ms-4">
                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                No Repeating Tasks Set! :)
                            </p>
                        </div>
            `;

            return;
        }

        let repeatingTasksList = document.getElementById("repeat-tasks-list");

        if (repeatingTasksList == null) {
            repeatTaskDisplay.innerHTML = `
            <div class="relative flex items-center mb-4">
        <h5 class="text-xl font-medium text-gray-500 dark:text-gray-400 mr-2" style="text-decoration: underline">Repeating Tasks</h5>
        <svg class="w-6 h-6 text-gray-800 dark:text-white" style="margin-left: 255px; cursor:pointer;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16" onclick="toggleRepeatTaskDisplayVisbility()">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
        </svg>

    </div>

    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-bold leading-none text-gray-500 dark:text-white">Task</h5>
        <h5 class="text-xl font-bold text-gray-500 dark:text-white">Due</h5>
    </div>

    <!-- Task List -->
    <div class="flow-root">
        <ul id="repeat-tasks-list" role="list" class="divide-y divide-gray-200 dark:divide-gray-700"
        style="overflow-y: auto; max-height: 1160px;">

        </ul>
    </div>

            `;
        }

        repeatTasks.forEach(repeatingTask => {
            let repeatEle = document.createElement("li");

            console.log("setting id for repeat: ", `repeating-task-${repeatingTask["id"]}-${repeatingTask["time_due"]}`);

            repeatEle.setAttribute("id", `repeating-task-${repeatingTask["id"]}-${repeatingTask["time_due"]}`)

            repeatEle.className            = "py-3 sm:py-4";
            repeatEle.style.textDecoration = "none";
            console.log(repeatEle);

            let dayDateObj = dateFns.parse(repeatingTask["date_due"], "dd/MM/yyyy", new Date());
            let day        = dateFns.format(dayDateObj, "EEEE");

            console.log("dayyyy ", day);

            repeatEle.innerHTML = `
            <div class="flex items-center">
                    <div class="flex-shrink-0"
                         onclick="alterEditFormVisibility(event)"
                         style="cursor: pointer;" >
                        <img class="w-8 h-8 rounded-full"
                             src="https://via.placeholder.com/150"
                             alt="Task Image">
                    </div>
                    <div class="flex-1 min-w-0 ms-4" style="cursor: default;" >
                        <p id='repeating-task-${repeatingTask["id"]}-task' class="text-md font-semibold text-gray-500 truncate dark:text-gray-400">
                            ${repeatingTask["task"]}
                        </p>
                        <p id='repeating-task-${repeatingTask["id"]}-description' class="text-sm text-gray-500 truncate dark:text-gray-400">
                            ${repeatingTask["description"]}
                        </p>
                    </div>

                    <div id='repeating-task-${repeatingTask["id"]}-time-due'
                         class="inline-flex items-center text-base font-semibold text-gray-500 dark:text-white">
                        ${repeatingTask["time_due"]}
                    </div>

                    <div style="display: none">${repeatingTask["id"]}</div>
                                        <div style="display: none">${repeatingTask["date_due"]}</div>
                                        <div style="display: none">${repeatingTask["repeat"]}</div>
                                        <div style="display: none">${repeatingTask["time_due"]}</div>
                                        <div style="display: none">${day}</div>
                                        <div style="display: none">0</div>

                </div>
            `;

            repeatingTasksList.append(repeatEle);
        })
    }

</script>
