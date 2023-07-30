<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="/style.css" />

        <title>Laravel task</title>
    </head>

    <body>
        <div id="app">
            <div id="projects">
                <div class="header">Projects</div>
                <div class="content">
                    <center v-if="!projects || projects.length == 0">
                        <i v-if="projects === null">Loading projects...</i>
                        <i v-if="projects !== null && projects.length == 0">There are no projects yet</i>
                    </center>
                    <ul>
                        <li v-for="project in projects" :key="project.id" :class="(selectedProjectId == project.id) ? 'is-active' : ''" :data-id="project.id" @click="selectProject(project.id)">
                            <div class="project-name">@{ project.name }</div>
                            <button @click="removeProject(project.id)">+</button>
                        </li>
                    </ul>
                    <button @click="showAddProjectForm = !showAddProjectForm">
                        <span v-if="showAddProjectForm">Hide the form</span>
                        <span v-if="!showAddProjectForm">Add project</span>
                    </button>
                    <form v-if="showAddProjectForm" @submit.prevent="addProject">
                        <input type="text" v-model="newProjectName" placeholder="Enter roject name">
                        <button type="submit">Add project</button>
                    </form>
                </div>
            </div>
            <div id="tasks">
                <div class="header">Tasks</div>
                <div class="content" v-if="selectedProjectId != null">
                    <center v-if="!tasks || tasks.length == 0">
                        <i v-if="tasks !== null && tasks.length == 0">There are no tasks in this project</i>
                    </center>
                    <ul>
                        <li v-for="task in tasks" :key="task.id" ref="taskItem" :data-id="task.id" :data-priority="task.priority">
                            <div class="task-name">@{ task.name }</div>
                            <div class="task-priority">Priority: @{ task.priority }</div>
                            <button @click="removeTask(task.id)">+</button>
                        </li>
                    </ul>
                    <button v-if="selectedProjectId !== null" @click="showNewTaskForm = !showNewTaskForm">
                        <span v-if="showNewTaskForm">Hide the form</span>
                        <span v-if="!showNewTaskForm">Add task</span>
                    </button>
                    <form v-if="showNewTaskForm" @submit.prevent="addTask">
                        <input type="text" v-model="newTaskName" placeholder="Enter task name">
                        <input type="number" v-model="newTaskPriority" placeholder="Enter task priority">
                        <button type="submit">Add task</button>
                    </form>
                </div>
            </div>
        </div>
    </body>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script type="text/javascript" src="/app.js"></script>
</html>
