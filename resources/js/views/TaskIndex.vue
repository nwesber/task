<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        My Tasks

                        <button @click="createTask" class="btn btn-primary btn-sm">Create Task</button>
                    </div>
                    <div class="card-body">
                       <div class="table-responsive">
                            <table class="table table-borderless">
                                <caption>List of tasks</caption>
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(task, index) in tasks" v-bind:key="index" v-bind:class="[ task.checked ? 'tdstrike' : '']">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input @click="completeTask(task)" :checked="task.checked" type="checkbox" :id="'customCheck' + index" class="custom-control-input">
                                                <label class="custom-control-label" :for="'customCheck' + index"></label>
                                            </div>
                                        </td>
                                        <td>{{ task.title }}</td>
                                        <td>{{ task.description }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" @click="editTask(task)">Edit</button>
                                            <button class="btn btn-danger btn-sm" @click="deleteTask(task)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Modal -->
        <div class="modal fade" id="updateTask" tabindex="-1" role="dialog" aria-labelledby="updateTask" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateTask">Update Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateTask">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <strong>Title: <span class="text-danger">*</span></strong>
                                    <input type="text" class="form-control mt-1" v-model="task.title" required />
                                </div>
                                <div class="form-group col-md-12">
                                    <strong>Description: <span class="text-danger">*</span></strong>
                                    <textarea type="text" class="form-control mt-1" v-model="task.description" required>
                                    </textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-primary">Update Task</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

         <!-- Create Modal -->
        <div class="modal fade" id="createTask" tabindex="-1" role="dialog" aria-labelledby="createTask" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTask">Create Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="storeTask">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <strong>Title: <span class="text-danger">*</span></strong>
                                    <input type="text" class="form-control mt-1" v-model="task.title" required />
                                </div>
                                <div class="form-group col-md-12">
                                    <strong>Description: <span class="text-danger">*</span></strong>
                                    <textarea type="text" class="form-control mt-1" v-model="task.description" required>
                                    </textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-primary">Save Task</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            tasks: null,
            task: {
                title: null,
                description: null
            }
        };
    },

    mounted(){
        this.fetchTasks()
    },

    methods: {
        fetchTasks(){
            axios.get(route('api.tasks.index'))
                .then(resp => {
                    this.tasks = []
                    this.tasks = resp.data.data
                })
                .catch(error => {
                    window.location = '/login'
                });
        },

        completeTask(task){
            task.checked = !task.checked

            axios.put(route('api.tasks.done', task.id),{
                checked: task.checked
            }).then(resp => {
                this.fetchTasks()
            })
        },

        createTask(){
            this.task.title = null;
            this.task.description = null;
            $('#createTask').modal('toggle')
        },

        editTask(task){
            this.task = task;
            $('#updateTask').modal('toggle')
        },

        storeTask(){
            axios.post(route('api.tasks.store'), this.task)
                .then(resp => {
                    this.fetchTasks()
                    $('#createTask').modal('toggle')
                });
        },

        updateTask(){
            axios.put(route('api.tasks.update', this.task.id), this.task)
            .then(resp => {
                this.fetchTasks()
                $('#updateTask').modal('toggle')
            })
        },

        deleteTask(task){
            axios.delete(route('api.tasks.destroy', task.id))
                .then(resp => {
                    this.fetchTasks()
                })
        }
    }
}
</script>

<style scoped>
    .tdstrike{
        text-decoration: line-through;
    }
</style>