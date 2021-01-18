<template>
    <div>
        <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="/img/jantahouse-favicon.png" alt="Logo" class="brand-image elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">jantahouse</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img :src="getProfilePhoto()" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ this.form.name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
          
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                <router-link to="/dashboard" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                   Dashboard
                  </p>
                </router-link>
              </li>    
              <li class="nav-item">
                <router-link to="/users" class="nav-link">
                  <i class="nav-icon fas fa-users nav-icon"></i>
                  <p>Users</p>
                </router-link>
              </li>
          <li class="nav-item">
            <router-link to="/profile" class="nav-link">
              <i class="nav-icon fas fa-user-alt"></i>
              <p>
                Profile
              </p>
            </router-link>
          </li>
                 <li class="nav-item">
            <a href="/logout"
            onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();"class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
               Logout
              </p>
            </a>
            <form id="logout-form" action="/logout" method="POST" class="d-none">
              <input type="hidden" name="_token" :value="csrf">
          </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
    </div>
</template>

<script>
    export default {
          data(){
        return{
              form: new Form({
              name: "",
              photo: "",
            }),
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      },
    methods:{
              getProfilePhoto(){
                    let photo = "img/profile.png";
                    if (this.form.photo) {
                photo = (this.form.photo.length > 200 ) ? this.form.photo: "img/profile/"+this.form.photo
                    }
                    return photo
              },
            loadProfile(){
                axios.get('api/profile')
            .then(({data}) => (this.form.fill(data)))
            }
    },
    created(){
        
        this.loadProfile()
        Fire.$on('RefreshSidebar',() => {
            this.loadProfile()
        })
      },
}
</script>
