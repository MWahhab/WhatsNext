<?php
$days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
?>

<div id="creation-form"
    class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700 fixed inset-0 m-auto"
    style="z-index: 1000; width: 90%; max-width: 500px; display: none">
    <div class="relative flex items-center mb-4">
        <h5 class="text-xl font-medium text-gray-500 dark:text-gray-400 mr-2">Create your new task here!</h5>
        <svg class="w-6 h-6 text-gray-800 dark:text-white" style="margin-left: 159px; cursor:pointer;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16" onclick="toggleCreationFormVisibility()">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
        </svg>

    </div>

    <label class="inline-flex items-center mb-5 cursor-pointer">
        <input id="repeat" name="repeat" value="off" type="checkbox" class="sr-only peer" checked
               onclick="toggleCreationForm()">
        <div
            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">One time task?</span>
    </label>

    <div id="repeat-on" style="display: none">

        <label class="inline-flex items-center mb-5 cursor-pointer">
            <input id="interval-selector" value="off" name="repeat-interval-query" type="checkbox" class="sr-only peer"
                   style="display: none"
                   onclick="toggleSlider()">
            <div
                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300 repeat-intervals">Repeat task in intervals?</span>
        </label>


        <div id="slider-div" style="display: none">
            <label for="minmax-range" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                the
                number of weeks between each interval (<span id="range-slider">4 weeks</span>)</label>
            <input id="minmax-range" name="repeat_interval" type="range" min="1" max="8" value="4"
                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                   onchange="displaySliderVal()">
        </div>

        <div id="task-creation-days" class="flex flex-wrap" style="display: block">
            <?php foreach ($days as $day) : ?>

            <div class="flex items-center me-4">
                <input id="<?= $day?>-checkbox" name="<?= $day?>" type="checkbox"
                       class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="<?= $day?>-checkbox"
                       class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"><?= $day ?></label>
            </div>

            <?php endforeach; ?>

        </div>

    </div>

    <br>

    <x-datepicker id="date_due" name="date_due" placeholder="Select Due Date" onchange="alterDynamicDay()"/>

    <span
        id="dynamic_day"
        class="text-sm font-medium text-gray-900 dark:text-white ms-3">Dynamic day goes here <br></span>

    <br>

    <x-timepicker id="time_due" name="time_due"/>

    <ul role="list" class="space-y-5 my-7">

        <div>
            <label for="task" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Task</label>
            <input type="text" id="task"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Enter Task Name" name="task" required/>
        </div>

        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
        <textarea id="description" name="description" rows="4"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Enter Task Description" required></textarea>

        <button id="task-creation-submit" type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-200
                dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5
                py-2.5 inline-flex justify-center w-full text-center" onclick="submitNewTask()">
            Create Task
        </button>
    </ul>

</div>

<script src="
https://cdn.jsdelivr.net/npm/date-fns@3.6.0/cdn.min.js
"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found.');
    }

    function toggleCreationFormVisibility(taskDate = null) {
        let form = document.getElementById("creation-form");

        if(form.style.display == "none") {
            document.getElementById("date_due").value = taskDate;

            alterDynamicDay(taskDate);

            let editForm = document.getElementById("editt-form");

            if (editForm.style.display === "block") {
                editForm.style.display = "none";
            }

            let repeatingTaskDisplay = document.getElementById("repeat-tasks-display");

            repeatingTaskDisplay.style.display = "none";

            form.style.display = "block";

            return;
        }

        form.style.display = "none";

        document.getElementById("time_due").value     = "00:00";
        document.getElementById("task").value         = "";
        document.getElementById("description").value  = "";
        document.getElementById("minmax-range").value = 4;
        document.getElementById("range-slider").innerText = "4 weeks";
        document.getElementById("repeat").checked     = true;
        toggleCreationForm();

        {{--@foreach($days as $day)--}}
        {{--document.getElementById("{{ $day }}").removeAttribute("checked");--}}
        {{--@endforeach--}}
    }

    function alterDynamicDay(taskDate = null) {
        if(taskDate == null) {
            taskDate = document.getElementById("date_due").value;
        }

        // Parse the date string in `d/m/y` format
        const date = dateFns.parse(taskDate, 'dd/MM/yyyy', new Date());

        // Get the day of the week
        document.getElementById("dynamic_day").innerHTML = dateFns.format(date, 'EEEE') + '<br>'; // 'EEEE' returns the full name of the day

    }

    function submitNewTask() {
        let taskObj = generateNewTaskObj();

        toggleCreationFormVisibility();

        console.log(taskObj);

        axios.post("{{ route("objective.store") }}", taskObj)
            .then(function (response) {

                console.log("returned data:", response.data);

                presentNewTask(response.data);
            })
            .catch(function (error) {
                console.log('Error fetching data:', error);
            });
    }

    function presentNewTask(data) {
        console.log("presenting new task data: ", data);

        let datesToView = isDateInRange(data);

        console.log("dates to view: ", datesToView);

        if(datesToView.length < 1) {
            return;
        }

        console.log("passed if statement: ", datesToView);

        for(let i = 0; i < datesToView.length; i++) {
            let dayOfWeek = dateFns.format(dateFns.parse(datesToView[i], 'dd/MM/yyyy', new Date()), 'EEEE');

            console.log("Day in datestoview foreach: ", dayOfWeek);

            dayOfWeek = isWithinLast6Days(datesToView[i]) ? dayOfWeek.toLowerCase() : dayOfWeek;

            console.log("new Day in datestoview foreach: ", dayOfWeek);

            if(!document.getElementById(datesToView[i] + "-card")){
                let parentDiv = document.getElementById(`inner-${dayOfWeek}-card`);

                parentDiv.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                        <h5 class="text-xl font-bold leading-none text-gray-500 dark:text-white">Task</h5>
                        <h5 class="text-xl font-bold text-gray-500 dark:text-white">
                            Due
                        </h5>
                        <svg class="w-6 h-6 text-gray-800 dark:text-white leading-none" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 20">
                            <path
                                d="M18.012 13.453c-.219-1.173-2.163-1.416-2.6-3.76l-.041-.217c0 .006 0-.005-.007-.038v.021l-.017-.09-.005-.025v-.006l-.265-1.418a5.406 5.406 0 0 0-5.051-4.408.973.973 0 0 0 0-.108L9.6 1.082a1 1 0 0 0-1.967.367l.434 2.325a.863.863 0 0 0 .039.1A5.409 5.409 0 0 0 4.992 9.81l.266 1.418c0-.012 0 0 .007.037v-.007l.006.032.009.046v-.01l.007.038.04.215c.439 2.345-1.286 3.275-1.067 4.447.11.586.22 1.173.749 1.074l12.7-2.377c.523-.098.413-.684.303-1.27ZM1.917 9.191h-.074a1 1 0 0 1-.924-1.07 9.446 9.446 0 0 1 2.426-5.648 1 1 0 1 1 1.482 1.343 7.466 7.466 0 0 0-1.914 4.449 1 1 0 0 1-.996.926Zm5.339 8.545A3.438 3.438 0 0 0 10 19.1a3.478 3.478 0 0 0 3.334-2.5l-6.078 1.136Z"/>
                        </svg>
                    </div>
                    <div class="flow-root">
                        <ul id="${datesToView[i]}-card" role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        </ul>
                    </div>
                    `;
            }

            let viewCard = document.getElementById(datesToView[i] + "-card");

            let childElement = document.createElement('li');
            childElement.id  = `${datesToView[i]}-${data["time_due"]}`;

            childElement.className            = 'py-3 sm:py-4 listing-' + data["obj_id"];
            childElement.style.textDecoration = 'none';

            childElement.innerHTML = `
    <div class="flex items-center">
        <div class="flex-shrink-0" style="cursor: pointer;" onclick="alterEditFormVisibility(event)">
            <img class="w-8 h-8 rounded-full" src="https://www.shutterstock.com/image-vector/write-text-create-edit-document-260nw-1696455538.jpg" alt="Neil image">
        </div>
        <div class="flex-1 min-w-0 ms-4" style="cursor: pointer;" onclick='changeTaskStatus("${data["date_due"]}", ${JSON.stringify(data)})'>
            <p id="${data['obj_id']}-${data['date_due']}-task" class="text-md font-semibold truncate dark:text-gray-400SSS task-${data['obj_id']}" style="color: #28A745">
                ${data["task"]}
            </p>
            <p id="${data['obj_id']}-${data['date_due']}-description" class="text-sm truncate dark:text-gray-400SSS description-${data['obj_id']}" style="color: #28A745">
                ${data["description"]}
            </p>
        </div>
        <div id="${data['obj_id']}-${data['date_due']}-time"
                                            class="inline-flex items-center text-base font-semibold dark:text-white time-${data['obj_id']}" style="color: #28A745">
            ${data["time_due"]}
        </div>

        <div style="display: none">${data["obj_id"]}</div>
                                        <div style="display: none">${data["date_due"]}</div>
                                        <div style="display: none">${data["repeat"]}</div>
                                        <div style="display: none">${data["time_due"]}</div>
                                        <div style="display: none">1</div>
    </div>
`;

            console.log("chileEle: ", childElement);
            console.log("viewCard: ", viewCard);

            viewCard.appendChild(childElement);
        }

        displayRepeatTasks(
            [
                {
                    "time_due"   : data["time_due"],
                    "id"         : data["obj_id"],
                    "date_due"   : data["date_due"],
                    "task"       : data["task"],
                    "description": data["description"],
                    "repeat"     : data["repeat"] == 1
                }
            ]
        );

        let repeatTaskEle = document.getElementById(`repeating-task-${data["obj_id"]}-task`);
        let repeatTimeEle = document.getElementById(`repeating-task-${data["obj_id"]}-time-due`);
        let repeatDescEle = document.getElementById(`repeating-task-${data["obj_id"]}-description`);

        repeatTaskEle.style.color = "#28A745";
        repeatTimeEle.style.color = "#28A745";
        repeatDescEle.style.color = "#28A745";

        }

    function isDateInRange(data) {
        let viewDateListing = document.querySelectorAll('.headings');

        let firstDate = data["repeat"] == 1 ? data["date_due"] : viewDateListing[0].id.slice(0, 10);
        let finalDate = viewDateListing[viewDateListing.length-1].id.slice(0, 10);

        const formattedDateToCheck = dateFns.parse(data["date_due"], 'd/M/yyyy', new Date());
        const formattedFirstDate   = dateFns.subDays(dateFns.parse(firstDate, 'd/M/yyyy', new Date()), 1);
        const formattedLastDate    = dateFns.addDays(dateFns.parse(finalDate, 'd/M/yyyy', new Date()), 1);

        console.log("date to check:", formattedDateToCheck, ". first date: ", formattedFirstDate, ". last date: ", formattedLastDate);

        const isInRange = dateFns.isWithinInterval(formattedDateToCheck, { start: formattedFirstDate, end: formattedLastDate });

        console.log("iswithininterval: ", isInRange);

        if(isInRange === false) {
            return [];
        }

        if(data["repeat"] !== 1 || data["repeat_interval"] > 1) {
            return [data["date_due"]];
        }

        if(data["repeat"] == 1 && !data["repeat_interval"]) {
            let allDaysOfInterval = dateFns.eachDayOfInterval({start: dateFns.addDays(formattedFirstDate, 1), end: dateFns.subDays(formattedLastDate, 1)});
            console.log("allDaysOfInterval: ", allDaysOfInterval);

            let daysRepeating = Object.keys(data).filter(key => data[key] === "on");
            console.log("daysRepeating: " , daysRepeating);
            let viewDates     = [];

            for(let j = 0; j < daysRepeating.length; j++) {
                for(let i = 0; i < allDaysOfInterval.length; i++) {
                    if(daysRepeating[j] === dateFns.format(allDaysOfInterval[i], 'EEEE').toLowerCase()) {
                        viewDates.push(dateFns.format(allDaysOfInterval[i], 'dd/MM/yyyy'));
                    }
                }
            }

            console.log("repeat view daysss:", viewDates);

            return viewDates;
        }

        if(data["repeat_interval"] == 1) {
            let secondDateToCheck = dateFns.addDays(formattedDateToCheck, 7);

            console.log("second date to check: ", secondDateToCheck);

            const secondInRange = dateFns.isWithinInterval(secondDateToCheck, { start: formattedFirstDate, end: formattedLastDate });

            console.log("second check results: ", secondInRange);

            let arr = [firstDate, dateFns.format(secondDateToCheck, 'dd/MM/yyyy')];
            console.log("range checked arr: ", arr);

            return secondInRange ? [data["date_due"], dateFns.format(secondDateToCheck, 'dd/MM/yyyy')] : [data["date_due"]];
        }
    }

    // function isSevenDaysAfter(date1Str, date2Str, dateFormat = 'dd/MM/yyyy') {
    //     let date1 = dateFns.parse(date1Str, dateFormat, new Date());
    //     let date2 = dateFns.parse(date2Str, dateFormat, new Date());
    //
    //     const date1Plus7Days = dateFns.addDays(date1, 7);
    //
    //     console.log("issameday :", dateFns.format(date1Plus7Days, "EEEE") == dateFns.format(date2, "EEEE"));
    //
    //     return dateFns.format(date1Plus7Days, "EEEE") == dateFns.format(date2, "EEEE");
    // }

    function isWithinLast6Days(date1Str, dateFormat = 'dd/MM/yyyy') {
        let date1 = dateFns.parse(date1Str, dateFormat, new Date());

        let viewDateListing = document.querySelectorAll('.headings');

        let finalDate = viewDateListing[viewDateListing.length-1].id.slice(0, 10);

        console.log("day date for last 6 days check: " , date1Str)
        console.log("final date for last 6 days check: " , finalDate)

        let date2 = dateFns.parse(finalDate, dateFormat, new Date());

        const daysDifference = dateFns.differenceInDays(date1, date2);

        console.log("daysDiff: ", daysDifference);

        // Checking if date1 is within 6 days before date2
        return daysDifference <= 6 && daysDifference >= -6;
    }

    function generateNewTaskObj() {
        let taskObj = {};

        console.log("task obj initially:", taskObj);

        let taskDetails =
            [
                "task",
                "description",
                "repeat",
                "date_due",
                "time_due"
            ];

        taskDetails.forEach((taskDetail) => {
            taskObj[taskDetail] = document.getElementById(taskDetail).value;
        })

        if (taskObj["repeat"] === "off") {
            return taskObj;
        }

        if (document.getElementById("interval-selector").checked) {
            taskObj["interval"] = document.getElementById("minmax-range").value;

            return taskObj;
        }

        let days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];

        days.forEach((day) => {
            let dayEle = document.getElementById(day + "-checkbox");

            if (dayEle.checked) {
                taskObj[day] = "on";
            }
        })

        return taskObj;

    }

    function toggleCreationForm() {
        let repeatToggle = document.getElementById("repeat");

        let taskDate   = document.getElementById("date_due");
        let dynamicDay = document.getElementById("dynamic_day");

        let repeatOnDiv = document.getElementById("repeat-on");

        if (repeatToggle.checked) {
            repeatOnDiv.style.display = "none";
            dynamicDay.style.display  = "block";
            repeatToggle.value        = "off";

            taskDate.setAttribute("placeholder", "Select Due Date");


            return;
        }

        repeatOnDiv.style.display = "block";
        dynamicDay.style.display  = "none";
        repeatToggle.value        = "on";

        taskDate.setAttribute("placeholder", "Select Start Date");

    }

    function toggleSlider() {
        let toggle = document.getElementById("interval-selector");
        let slider = document.getElementById("slider-div");
        let taskDaysCheckboxes = document.getElementById("task-creation-days");

        if (toggle.checked) {
            slider.setAttribute("required", "");

            slider.style.display             = "block";
            taskDaysCheckboxes.style.display = "none";
            toggle.value                     = "on";

            return;
        }
        slider.removeAttribute("required");

        slider.style.display             = "none";
        taskDaysCheckboxes.style.display = "block";
        toggle.value                     = "off";
    }

    function displaySliderVal() {
        let sliderVal = document.getElementById("minmax-range").value;

        document.getElementById("range-slider").textContent = sliderVal < 2 ? "1 Week" : sliderVal + " Weeks";
    }

</script>

