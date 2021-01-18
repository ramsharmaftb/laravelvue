<template>
  <div class="container">
    <br /><br />
    <div v-if="!$gate.isAdmin()">
      <not-found></not-found>
    </div>
    <div class="row" v-if="$gate.isAdmin()">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Users Table</h3>

            <div class="card-tools">
              <button
                class="btn btn-sm btn-success"
                @click="newModal"
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
                  <th>S.NO</th>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Registered At</th>
                  <th>Modify</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user,index) in users.data" :key="user.id">
                  <td>{{ (index+1) }}</td>
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.type | upText }}</td>
                  <td>{{ user.created_at | dateFormat }}</td>
                  <td>
                    <button class="btn btn-sm btn-primary" @click="editModal(user)">
                      <i class="fa fa-edit"></i>
                    </button>
                    &nbsp;&nbsp;
                    <button
                      class="btn btn-sm btn-danger"
                      @click="deleteUser(user.id)"
                    >
                      <i class="fa fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
              <div class="card-footer">
                <pagination align="right" :show-disabled=true :data="users" @pagination-change-page="getResults"></pagination>

              </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
    <div
      class="modal fade"
      id="userModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-show="!editmode" id="exampleModalLabel">Add New User</h5>
            <h5 class="modal-title" v-show="editmode" id="exampleModalLabel">Update User</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editmode ? updateUser() : createUser()">
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
                  placeholder="Enter New Password"
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
              <button v-show="!editmode" type="submit" class="btn btn-sm btn-primary">Create</button>
              <button v-show="editmode" type="submit" class="btn btn-sm btn-success">Update</button>
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
      editmode:false,
      // current_page: {},
      users: {},
      form: new Form({
        id: "",
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
    // Our method to GET results from a Laravel endpoint
		getResults(page = 1) {
			axios.get('api/user?page=' + page)
				.then(response => {
					this.users = response.data;
				});
		},
    updateUser(){
       this.$Progress.start()
        this.form.put('api/user/'+this.form.id)
        .then(() => {
          $("#userModal").modal("hide")
             Swal.fire("Updated!", "User has been Updated.", "success");
            Fire.$emit("RefreshList");
          this.$Progress.finish()

        })
        .catch(() => {
          Swal.fire("Failed!", "There was something wrong", "warning")
           this.$Progress.fail()
        })
    },
    editModal(user){
      this.editmode = true
      this.form.reset()
      this.form.clear()
      $("#userModal").modal("show")
      this.form.fill(user)
   },
    newModal(){
        this.editmode = false
        this.form.reset()
        this.form.clear()
       $("#userModal").modal("show")
    },
    deleteUser(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        //Send request to server
        if (result.isConfirmed) {
        this.form.delete("api/user/" + id)
          .then(() => {
            Swal.fire("Deleted!", "Your file has been deleted.", "success")
            Fire.$emit("RefreshList");
          })
          .catch(() => {
            Swal.fire("Failed!", "There was something wrong", "warning")
          })
        }
      })
      
    },
    loadUsers() {
      if(this.$gate.isAdmin()){

        axios.get("api/user").then(({ data}) => (this.users = data))
      }
    },
    createUser() {
      
      this.$Progress.start()
      this.form
        .post("api/user")
        .then(() => {
          Fire.$emit("RefreshList")
          $("#userModal").modal("hide")

          toast.fire({
            icon: "success",
            title: "User Created successfully",
          })

          this.$Progress.finish()
        })
        .catch(() => {
          this.$Progress.fail()
        })
    },
  },
  created() {
    // this.$router.replace({ path: "users", query: {page: this.users.current_page } })
    Fire.$on('searching',() => {
      let query = this.$parent.search
      axios.get('api/finduser?q='+query)
      .then((data2) => {
         this.users = data2.data
        console.log(data2)
     })
      .catch(()=>{
          console.log('wrooooong')
      })
    })
    this.loadUsers()
    Fire.$on("RefreshList", () => {
      this.loadUsers()
    });
    //setInterval(() => this.loadUsers(),3000)
  },
};
</script>
