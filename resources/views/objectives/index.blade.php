<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ config("app.name") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative">
            <div class="flex justify-center items-center relative">
                <x-datepicker id="week-selector" value="{{ reset($datesWithObjectives)['date'] }}" placeholder="Select viewing date"/>
                <svg class="w-6 h-6 text-white ml-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18" style="cursor: pointer" onclick="toggleRepeatTaskDisplayVisbility()">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 14 3-3m-3 3 3 3m-3-3h16v-3m2-7-3 3m3-3-3-3m3 3H3v3"/>
                </svg>
            </div>
        </div>




        <x-view-days :dates-with-objectives="$datesWithObjectives"/>

    </div>

    @include("objectives.edit")
    @include("objectives.store")

    <x-repeat-tasks />


    <footer class="fixed bottom-0 left-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://flowbite.com/" class="hover:underline">Flowbite™</a>. All Rights Reserved.
    </span>
        <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
            <li>
                <a href="#" class="hover:underline me-4 md:me-6">About</a>
            </li>
            <li>
                <a href="#" class="hover:underline me-4 md:me-6">Privacy Policy</a>
            </li>
            <li>
                <a href="#" class="hover:underline me-4 md:me-6">Licensing</a>
            </li>
            <li>
                <a href="#" class="hover:underline">Contact</a>
            </li>
        </ul>
    </footer>


</x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    window.onload = function () {
        retrieveRepeatTasks();
        attachDatepickerListener();
    };

    function attachDatepickerListener (){
        let datepicker = document.getElementById("week-selector");
        datepicker.addEventListener('changeDate', function () {
                     retrieveObjectives(datepicker);
                 });

        console.log(datepicker);
    }

    function retrieveObjectives(weekSelector) {
        console.log("ive made it to this point");

        console.log("selected: ", weekSelector.value);

        axios.post('objective/objectivesRetrieval', {
            selectedDate: weekSelector.value
        })
            .then(function (response) {
                console.log(response);
                if (response.data.length <= 0) {
                    console.log("Issue retrieving week's objectives!")
                    return;
                }

                setNewView(response.data);
            })
            .catch(function (err) {
                console.log("Error fetching week's objectives:", err);
            })
    }

    function setNewView(data) {
        let viewContainer = document.getElementById("view-container");

        console.log("daysCard number: ", viewContainer.childElementCount);
        console.log("data: ", data);

        let dayCards = Array.from(viewContainer.children);

        for(let i=0; i<dayCards.length; i++){
            let count = 0;
            let day;
            let objectives;
            let date;

            console.log("daysCard: ", dayCards[i]);

            for (const [dayKey, dateWithObjectives] of Object.entries(data)) {
                if(count !== i){
                    count++;

                    continue;
                }

                day        = dayKey;
                objectives = dateWithObjectives["objectives"] ? dateWithObjectives["objectives"] : null;
                date       = dateWithObjectives["date"];

                break;
            }

            dayCards[i].setAttribute("id", `${day}-card`);

            let innerDayCardEle = dayCards[i].firstElementChild;

            innerDayCardEle.setAttribute("id", `inner-${day}-card`);

            let h3Heading          = innerDayCardEle.nextElementSibling;
            let creationFormButton = h3Heading.nextElementSibling;

            h3Heading.setAttribute("id", `${date}-heading`);
            h3Heading.innerText = `${day.charAt(0).toUpperCase() + day.slice(1)} - ${date}`;

            creationFormButton.setAttribute("onclick", `toggleCreationFormVisibility("${date}")`)

            console.log("objectives: ", objectives);

            if(objectives == null){
                console.log("makes it ere");

                innerDayCardEle.innerHTML = `
                        <div id="${date}-taskless-view" class="flex-1 min-w-0 ms-4">
                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                No Chores For This Day! :)
                            </p>
                        </div>
                        `;

                continue;
            }

            if(innerDayCardEle.childElementCount<2){
                innerDayCardEle.innerHTML = `
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
                        <ul id="${date}-card" role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        </ul>
                    </div>
                    `;
            }

            let ulEle = innerDayCardEle.firstElementChild.nextElementSibling.firstElementChild;
            console.log("initial ulele: ", ulEle)

            ulEle.innerHTML = "";
            console.log("makes it here");

            Object.values(objectives).forEach(objective => {
                console.log("makes it here");
                let childElement = document.createElement('li');
                childElement.id  = `${date}-${objective["time_due"]}`;

                childElement.className            = 'py-3 sm:py-4 listing-' + objective["obj_id"];

                if(objective["accomplished"]){
                    childElement.style.textDecoration = "line-through";
                    childElement.style.opacity        = "0.5";
                }

                childElement.innerHTML = `
    <div class="flex items-center">
        <div class="flex-shrink-0 ${objective['accomplished'] ? 'pointer-events-none' : ''}"
        style="cursor: pointer;" onclick="alterEditFormVisibility(event)">
            <img class="w-8 h-8 rounded-full" src="https://www.shutterstock.com/image-vector/write-text-create-edit-document-260nw-1696455538.jpg" alt="Neil image">
        </div>
        <div class="flex-1 min-w-0 ms-4" style="cursor: pointer;" onclick='changeTaskStatus("${date}", ${JSON.stringify(objective)})'>
            <p id="${objective['obj_id']}-${date}-task" class="text-md font-semibold truncate text-gray-500 dark:text-gray-400SSS task-${objective['obj_id']}">
                ${objective["task"]}
            </p>
            <p id="${objective['obj_id']}-${date}-description" class="text-sm truncate text-gray-500 dark:text-gray-400SSS description-${objective['obj_id']}">
                ${objective["description"]}
            </p>
        </div>
        <div id="${objective['obj_id']}-${date}-time"
                                            class="inline-flex items-center text-base font-semibold text-gray-500 dark:text-white time-${objective['obj_id']}">
            ${objective["time_due"]}
        </div>

        <div style="display: none">${objective["obj_id"]}</div>
                                        <div style="display: none">${date}</div>
                                        <div style="display: none">${objective["repeat"]}</div>
                                        <div style="display: none">${objective["time_due"]}</div>
                                        <div style="display: none">1</div>
    </div>
`;

                console.log("chileEle: ", childElement);
                console.log("ulEle: ", ulEle);

                ulEle.appendChild(childElement);
            })


        }


    }

</script>
