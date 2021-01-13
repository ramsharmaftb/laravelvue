<template>
  <div class="container">
    <br /><br />
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Responsive Hover Table</h3>

            <div class="card-tools">
              <button
                class="btn btn-sm btn-success"
                data-toggle="modal"
                data-target="#addUserModal"
              >
                Add New <i class="fa fa-user-plus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Registered At</th>
                  <th>Modify</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.type | upText }}</td>
                  <td>{{ user.created_at | dateFormat }}</td>
                  <td>
                    <a href="#"> <i class="fa fa-edit indigo"></i></a
                    >&nbsp;&nbsp;<a href="#">
                      <i class="fa fa-trash red"></i
                    ></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <div
      class="modal fade"
      id="addUserModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="createUser" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="form-group">
                <input
                  placeholder="Name"
                  v-model="form.name"
                  type="text"
                  name="name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                />
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <input
                  placeholder="Email"
                  v-model="form.email"
                  type="text"
                  name="email"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                />
                <has-error :form="form" field="email"></has-error>
              </div>
              <div class="form-group">
                <textarea
                  placeholder="Enter Bio"
                  v-model="form.bio"
                  type="text"
                  name="bio"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('bio') }"
                ></textarea>
                <has-error :form="form" field="bio"></has-error>
              </div>
              <div class="form-group">
                <select
                  placeholder="type"
                  v-model="form.type"
                  type="text"
                  name="type"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('type') }"
                >
                  <option value="">Select User Role</option>
                  <option value="admin">Admin</option>
                  <option value="user">Standard User</option>
                  <option value="author">Author</option>
                </select>
                <has-error :form="form" field="type"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.password"
                  type="password"
                  name="password"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                />
                <has-error :form="form" field="password"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-sm btn-danger"
                data-dismiss="modal"
              >
                Close
              </button>
              <button type="submit" class="btn btn-sm btn-primary">
                Create
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- User Add Modal End -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: {},
      form: new Form({
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: "",
      }),
    };
  },
  methods: {
    loadUsers() {
      axios.get("api/user").then(({ data }) => (this.users = data.data))
    },
    createUser() {
      this.$Progress.start()
      this.form.post("api/user")
      .then(()=>{
            Fire.$emit('AfterCreated')
      $('#addUserModal').modal('hide')

      toast.fire({
        icon: "success",
        title: "User Created successfully",
      })

      this.$Progress.finish()
      })
      .catch(()=>{
          this.$Progress.fail()
      })
    
    },
  },
  created() {
    this.loadUsers()
    Fire.$on('AfterCreated',() => {
        this.loadUsers()
    })
    //setInterval(() => this.loadUsers(),3000)
  },
}
</script>
