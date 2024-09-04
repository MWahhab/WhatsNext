<style>
    .img-box {
        width: 100px;
        height: 900px;
        margin: 10px;
        border-radius: 50px;
        background: url('https://static.vecteezy.com/system/resources/thumbnails/040/966/108/small_2x/ai-generated-high-contrast-plain-navy-blue-background-photo.jpg') center;
        position: relative;
        transition: width 0.5s;
        overflow: hidden;
    }

    /*.img-box .task-card {*/
    /*    position: absolute;*/
    /*    top: -50px; !* Position above h3 *!*/
    /*    left: 50%;*/
    /*    transform: translateX(-50%);*/
    /*    width: 80%; !* Adjust width as needed *!*/
    /*    padding: 10px;*/
    /*    background-color: rgba(0, 0, 0, 0.8);*/
    /*    color: #fff;*/
    /*    text-align: center;*/
    /*    opacity: 0; !* Initially invisible *!*/
    /*    transition: opacity 0.5s, top 0.5s; !* Transition for visibility *!*/
    /*}*/

    .img-box h3 {
        color: #fff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-90deg);
        opacity: 1;
        transition: top 0.5s, left 0.5s, transform 0.5s, opacity 0.5s;
        white-space: nowrap; /* Prevent text wrapping */
        font-size: 24px;
    }

    .img-box svg {
        position: absolute;
        bottom: 25px;
        right: 30px;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.5s, transform 0.5s;
        width: 24px; /* Match the size of the text */
        height: 24px; /* Match the size of the text */
    }

    .img-box:hover {
        width: 500px;
    }

    /*.img-box:hover .task-card {*/
    /*    top: 10%; !* Slide the card in from above *!*/
    /*    opacity: 1; !* Make the card visible *!*/
    /*}*/

    .img-box:hover h3 {
        top: auto; /* Reset top to auto */
        bottom: 25px; /* Move to 25px from the bottom */
        left: 30px; /* Adjust position to bottom left */
        transform: translateY(6px); /* Lower to align with the SVG */
    }

    .img-box:hover svg {
        top: auto; /* Reset top to auto */
        bottom: 25px; /* Align vertically with h3 */
        left: auto; /* Reset left to auto */
        right: 30px; /* Position to bottom right */
        opacity: 1;
        transform: translateY(0); /* Maintain rotation */
    }
    .task-card {
        width: 100%; /* Occupy full width of img-box */
        height: auto; /* Allow height to expand */
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.8);
        margin-top: -15px;
        color: #fff;
        text-align: center;
        opacity: 0; /* Initially invisible */
        transition: opacity 0.5s, transform 0.5s; /* Transition for visibility */
        border-radius: 10px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1; /* Ensure it's above the background image */
    }

    /*!* General styling for the ul *!*/
    /*.task-card {*/
    /*    overflow-y: auto;*/
    /*    max-height: 160px; !* You can adjust this value *!*/
    /*    background-color: #fff; !* Background color for the list *!*/
    /*    padding: 10px; !* Padding inside the ul *!*/
    /*    border-radius: 10px; !* Rounded corners for the ul *!*/
    /*}*/

    .img-box:hover .task-card {
        opacity: 1; /* Make the card visible */
    }

    .task-card h5 {
        margin-bottom: 10px;
    }

    .task-card p {
        margin-bottom: 5px;
    }

    .task-card ul {
        margin-top: 5px;
        max-height: 670px; /* Limit the height of the list */
        overflow-y: auto; /* Add scroll if necessary */
        padding-right: 10px; /* Add some padding for scrollbar */
    }


    /* Scrollbar customization for WebKit-based browsers */
    .task-card ul::-webkit-scrollbar {
        width: 4px; /* Set the width of the scrollbar to be very thin */
        background: transparent; /* Hide scrollbar track by default */
    }

    .task-card ul::-webkit-scrollbar-track {
        background: transparent; /* Background of the scrollbar track (hidden) */
    }

    .task-card ul::-webkit-scrollbar-thumb {
        background-color: transparent; /* Hide the scrollbar thumb */
    }

    /* Show scrollbar on hover */
    .task-card ul:hover::-webkit-scrollbar-track {
        background: #f1f1f1; /* Show track on hover */
    }

    .task-card ul:hover::-webkit-scrollbar-thumb {
        background-color: #888; /* Show thumb color on hover */
        border-radius: 10px; /* Rounded edges on the thumb */
    }

    .task-card li {
        margin-bottom: 10px;
    }

    .task-card li:last-child {
        margin-bottom: 0;
    }

    .task-card img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }

</style>

<div
    style="width: 100%; height: 100vh; display: flex; align-items: center; justify-content: center; margin-top: -160px">
    <div id="view-container" style="display:flex; align-items: center; justify-content: center">
        @foreach($datesWithObjectives as $day => $dateWithObjectives)
            <div id="{{ $day }}-card" class="img-box">
                <div id="inner-{{ $day }}-card"
                    class="task-card w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">

                    @if(!isset($dateWithObjectives["objectives"]))
                        <div id="{{ $dateWithObjectives['date'] }}-taskless-view" class="flex-1 min-w-0 ms-4">
                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                No Chores For This Day! :)
                            </p>
                        </div></div>


                @else

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
                        <ul id="{{ $dateWithObjectives['date'] }}-card" role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($dateWithObjectives["objectives"] as $objective)
                                <li id="{{ $dateWithObjectives['date'] }}-{{ $objective['time_due'] }}"
                                    class="py-3 sm:py-4 listing-{{ $objective["obj_id"] }}"
                                    style="{{ isset($objective['accomplished']) ? 'text-decoration: line-through; opacity: 0.5;' : '' }}">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 {{ isset($objective['accomplished']) ? 'pointer-events-none' : '' }}"
                                             style="cursor: pointer;"
                                             onclick="alterEditFormVisibility(event)">
                                            <img class="w-8 h-8 rounded-full"
                                                 src="https://www.shutterstock.com/image-vector/write-text-create-edit-document-260nw-1696455538.jpg"
                                                 alt="Neil image">
                                        </div>
                                        <div class="flex-1 min-w-0 ms-4" style="cursor: pointer;" onclick='changeTaskStatus("{{ $dateWithObjectives['date'] }}", @json($objective))'>
                                            <p id="{{ $objective['obj_id'] }}-{{ $dateWithObjectives['date'] }}-task" class="text-md font-semibold text-gray-500 truncate dark:text-gray-400SSS task-{{ $objective['obj_id'] }}">
                                                {{ $objective["task"] }}
                                            </p>
                                            <p id="{{ $objective['obj_id'] }}-{{ $dateWithObjectives['date'] }}-description" class="text-sm text-gray-500 truncate dark:text-gray-400SSS description-{{ $objective['obj_id'] }}">
                                                {{ $objective["description"] }}
                                            </p>
                                        </div>

                                        <div id="{{ $objective['obj_id'] }}-{{ $dateWithObjectives['date'] }}-time"
                                            class="inline-flex items-center text-base font-semibold text-gray-500 dark:text-white time-{{ $objective['obj_id'] }}">
                                            {{ $objective["time_due"] }}
                                        </div>

                                        <div style="display: none">{{ $objective["obj_id"] }}</div>
                                        <div style="display: none">{{ $dateWithObjectives['date'] }}</div>
                                        <div style="display: none">{{ $objective['repeat'] }}</div>
                                        <div style="display: none">{{ $objective['time_due'] }}</div>
{{--                                        <div style="display: none">{{ $day }}</div>--}}
                                        <div style="display: none">1</div>

{{--                                        @if(isset($datesWithObjectives["unique"]))--}}
{{--                                            <div id="{{ $objective["obj_id"] }}-{{ $dateWithObjectives['date'] }}-unique">{{ $objective["unique"] }}</div>--}}
{{--                                        @endif--}}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
            </div>
            @endif

            <h3 id="{{ $dateWithObjectives["date"] }}-heading" class="headings">{{ ucfirst($day) }}
                - {{ $dateWithObjectives["date"] }}</h3>
            <svg class="w-6 h-6 text-gray-50 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 10 14" style="cursor: pointer;"
                 onclick="toggleCreationFormVisibility('{{ $dateWithObjectives["date"] }}')">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13V1m0 0L1 5m4-4 4 4"/>
            </svg>
    </div>
    @endforeach
</div>
</div>

<script>
    function changeTaskStatus(date, objective) {
        if(typeof objective == "string"){
            objective = JSON.parse(objective);
        }

        console.log(date);
        console.log("obj: ", objective);

        console.log(objective)
        console.log("tasklisting id = ", date + "-" + objective["time_due"]);

        let taskListing =
            document.getElementById(date + "-" + objective["time_due"]);

        console.log("taskslisttinggg: ", taskListing);

        if(taskListing.style.textDecoration === "line-through") {
            taskListing.style.textDecoration = "none";
            taskListing.style.opacity        = 1;

            taskListing.children[0].children[0].classList.remove("pointer-events-none");

            updateAccomplishment(false, objective["obj_id"], date);

            return;
        }

        document.getElementById("editt-form").style.display === "block" && alterEditFormVisibility();

        taskListing.style.textDecoration = "line-through";
        taskListing.style.opacity        = 0.5;

        taskListing.children[0].children[0].classList.add("pointer-events-none");

        updateAccomplishment(true, objective["obj_id"], date);
    }

    function updateAccomplishment(checkStatus, objectiveId, dateDue) {
        axios.put("amendment/" + objectiveId, {
            checked    : checkStatus,
            objectiveId: objectiveId,
            dateDue    : dateDue
        }).then(response => {
            console.log("Successful Update: ", response);
        }).catch(err => {
            console.log("Error: ", err);
        });
    }

{{--    function setNewView(data) {--}}
{{--        let viewContainer = document.getElementById("view-container");--}}

{{--        viewContainer.innerHTML = "";--}}

{{--        object.entries(data).forEach((day, dateWithObjectives) => {--}}
{{--            let innerDayCardEle = document.createElement("div");--}}

{{--            innerDayCardEle.className =--}}
{{--                "task-card w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700";--}}
{{--            innerDayCardEle.setAttribute("id", `inner-${day}-card`);--}}

{{--            if(dateWithObjectives["objectives"] == undefined){--}}
{{--                innerDayCardEle.innerHTML = `--}}
{{--                 <div id="${dateWithObjectives['date']}-taskless-view" class="flex-1 min-w-0 ms-4">--}}
{{--                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">--}}
{{--                                No Chores For This Day! :)--}}
{{--                            </p>--}}
{{--                        </div>--}}

{{--                `;--}}

{{--                return;--}}
{{--            }--}}

{{--            innerDayCardEle.innerHTML = `--}}
{{--            <div class="flex items-center justify-between mb-4">--}}
{{--                        <h5 class="text-xl font-bold leading-none text-gray-500 dark:text-white">Task</h5>--}}
{{--                        <h5 class="text-xl font-bold text-gray-500 dark:text-white">--}}
{{--                            Due--}}
{{--                        </h5>--}}
{{--                        <svg class="w-6 h-6 text-gray-800 dark:text-white leading-none" aria-hidden="true"--}}
{{--                             xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 20">--}}
{{--                            <path--}}
{{--                                d="M18.012 13.453c-.219-1.173-2.163-1.416-2.6-3.76l-.041-.217c0 .006 0-.005-.007-.038v.021l-.017-.09-.005-.025v-.006l-.265-1.418a5.406 5.406 0 0 0-5.051-4.408.973.973 0 0 0 0-.108L9.6 1.082a1 1 0 0 0-1.967.367l.434 2.325a.863.863 0 0 0 .039.1A5.409 5.409 0 0 0 4.992 9.81l.266 1.418c0-.012 0 0 .007.037v-.007l.006.032.009.046v-.01l.007.038.04.215c.439 2.345-1.286 3.275-1.067 4.447.11.586.22 1.173.749 1.074l12.7-2.377c.523-.098.413-.684.303-1.27ZM1.917 9.191h-.074a1 1 0 0 1-.924-1.07 9.446 9.446 0 0 1 2.426-5.648 1 1 0 1 1 1.482 1.343 7.466 7.466 0 0 0-1.914 4.449 1 1 0 0 1-.996.926Zm5.339 8.545A3.438 3.438 0 0 0 10 19.1a3.478 3.478 0 0 0 3.334-2.5l-6.078 1.136Z"/>--}}
{{--                        </svg>--}}
{{--                    </div>--}}
{{--                    <div class="flow-root">--}}
{{--                        <ul id="${dateWithObjectives['date']}-card" role="list" class="divide-y divide-gray-200 dark:divide-gray-700">`;--}}

{{--            dateWithObjectives['objectives'].forEach(objective => {--}}
{{--                let listingEle = document.createElement("li");--}}

{{--                listingEle.setAttribute("id", `${dateWithObjectives['date']}-${objective["time_due"]}`);--}}
{{--                listingEle.className = `py-3 sm:py-4 listing-${objective["obj_id"]}`;--}}

{{--                if(objective["accomplished"]){--}}
{{--                    listingEle.style.textDecoration = "line-through";--}}
{{--                    listingEle.style.opacity        = "0.5";--}}
{{--                }--}}

{{--                listingEle.innerHTML = `--}}
{{--                <div class="flex items-center">--}}
{{--                                        <div class="flex-shrink-0 ${objective['accomplished'] ? 'pointer-events-none' : ''}"--}}
{{--                                             style="cursor: pointer;"--}}
{{--                                             onclick="alterEditFormVisibility(event)">--}}
{{--                                            <img class="w-8 h-8 rounded-full"--}}
{{--                                                 src="https://www.shutterstock.com/image-vector/write-text-create-edit-document-260nw-1696455538.jpg"--}}
{{--                                                 alt="Neil image">--}}
{{--                                        </div>--}}
{{--                                        <div class="flex-1 min-w-0 ms-4" style="cursor: pointer;" onclick='changeTaskStatus("${dateWithObjectives['date']}", ${JSON.stringify(objective)})'>--}}
{{--                                            <p id="${objective['obj_id']}-${dateWithObjectives['date']}-task" class="text-md font-semibold text-gray-500 truncate dark:text-gray-400SSS task-${objective['obj_id']}">--}}
{{--                                                ${objective['task']}--}}
{{--                </p>--}}
{{--                <p id="${objective['obj_id']}-${dateWithObjectives['date']}-description" class="text-sm text-gray-500 truncate dark:text-gray-400SSS description-${objective['obj_id']}">--}}
{{--                                                ${objective['description']}--}}
{{--                </p>--}}
{{--            </div>--}}

{{--            <div id="${objective['obj_id']}-${dateWithObjectives['date']}-time"--}}
{{--                                            class="inline-flex items-center text-base font-semibold text-gray-500 dark:text-white time-${objective['obj_id']}">--}}
{{--                                            ${objective['time_due']}--}}
{{--                </div>--}}

{{--                <div style="display: none">${objective['obj_id']}</div>--}}
{{--                                        <div style="display: none">${dateWithObjectives['date']}</div>--}}
{{--                                        <div style="display: none">${objective['repeat']}</div>--}}
{{--                                        <div style="display: none">${objective['time_due']}</div>--}}
{{--                                        <div style="display: none">{{ $day }}</div>--}}
{{--                <div style="display: none">1</div>--}}

{{--                                        @if(isset($datesWithObjectives["unique"]))--}}
{{--                --}}{{--                                            <div id="{{ $objective["obj_id"] }}-{{ $dateWithObjectives['date'] }}-unique">{{ $objective["unique"] }}</div>--}}
{{--                --}}{{--                                        @endif--}}
{{--                </div>--}}
{{--`;--}}

{{--                innerDayCardEle.append(listingEle);--}}

{{--            })--}}

{{--                let endOfInnerCardEle = `--}}
{{--                </ul>--}}
{{--        </div>--}}
{{--</div>--}}
{{--<h3 id="${dateWithObjectives['date']}-heading" class="headings">{{ ucfirst($day) }}--}}
{{--                - ${dateWithObjectives['date']}</h3>--}}
{{--            <svg class="w-6 h-6 text-gray-50 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"--}}
{{--                 fill="none" viewBox="0 0 10 14" style="cursor: pointer;"--}}
{{--                 onclick="toggleCreationFormVisibility('${dateWithObjectives["date"]}')">--}}
{{--                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                      d="M5 13V1m0 0L1 5m4-4 4 4"/>--}}
{{--            </svg>--}}
{{--    </div>--}}

{{--                `;--}}

{{--                innerDayCardEle.append(endOfInnerCardEle);--}}

{{--                viewContainer.append(innerDayCardEle);--}}

{{--        }--}}
{{--    }--}}

</script>

