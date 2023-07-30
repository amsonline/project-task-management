new Vue({
    el: '#app',
    delimiters: ['@{', '}'], // Changing the delimiter to prevent conflict with Laravel blade
    data: {
        projects: null, // Initialize projects as an empty array
        selectedProjectId: null,
        tasks: null, // Initialize tasks as an empty array
        newTaskName: '', // To store the new task title entered in the form
        newTaskPriority: '', // To store the new task priority entered in the form
        newProjectName: '', // To store the new project name entered in the form
        showNewTaskForm: false,
        showAddProjectForm: false,
    },
    mounted() {
        this.fetchProjects(); // Fetch projects initially
    },
    methods: {
        fetchProjects() {
            // Fetch projects from Laravel API
            axios.get('/api/projects')
                .then(response => {
                    this.projects = response.data; // Assign fetched tasks to the tasks data property
                })
                .catch(error => {
                    console.error(error);
                });
        },
        addProject() {
            // Send the new project data to Laravel API
            axios.post('/api/projects', { name: this.newProjectName })
                .then(() => {
                    this.newProjectName = ''; // Clear the input field
                    this.fetchProjects();
                    this.showNewProjectForm = false;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        removeProject(id) {
            if (!confirm("Are you sure you want to remove the project and all tasks in it?")) {
                return;
            }
            // Send the new project data to Laravel API
            axios.delete(`/api/projects/${id}`)
                .then(() => {
                    this.tasks = null;
                    this.selectedProjectId = null;
                    this.fetchProjects();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        selectProject(id) {
            this.selectedProjectId = id;
            this.fetchTasks();
        },
        fetchTasks() {
            // Fetch tasks from your Laravel API using Axios
            axios.get(`/api/projects/${this.selectedProjectId}/tasks`)
                .then(response => {
                    this.tasks = response.data; // Assign fetched tasks to the tasks data property
                    this.initSortable();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        addTask() {
            // Send the new task to your Laravel API using Axios
            axios.post(`/api/projects/${this.selectedProjectId}/tasks`, { name: this.newTaskName, priority: this.newTaskPriority })
                .then(() => {
                    this.newTaskName = ''; // Clear the input field
                    this.newTaskPriority = ''; // Clear the input field
                    this.fetchTasks();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        initSortable() {
            // Initialize Sortable for the list of tasks
            const taskList = this.$el.querySelector('#tasks ul');
            new Sortable(taskList, {
                animation: 150,
                onEnd: (event) => {
                    // Get the updated task order after reordering
                    // To achieve this, we take a look at the priority of the item upper than the new place
                    // If there is no items above, we consider it 0
                    const newPriority = parseInt(taskList.children[event.newIndex - 1]?.dataset.priority ?? 0) + 1;

                    this.updateTaskOrder(event.item.dataset.id, newPriority);
                },
            });
        },
        updateTaskOrder(id, priority) {
            axios.put(`/api/tasks/${id}`, { priority })
                .then(() => {
                    this.fetchTasks();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        removeTask(id) {
            axios.delete(`/api/tasks/${id}`)
                .then(() => {
                    this.tasks = this.tasks.filter(task => task.id !== id);
                })
                .catch(error => {
                    console.error(error);
                });
        },
    },
});
