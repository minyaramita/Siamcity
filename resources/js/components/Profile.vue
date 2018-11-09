<style>
.widget-user-header{
  height: 180px !important;
}
</style>


<template>
    <div class="container">
        <div class="row justify-content-center mt-3">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info-active">
                        <h3 class="widget-user-username">{{this.form.name}}</h3>
                        <h5 class="widget-user-desc">{{this.form.type}}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                    </div>
                </div>
            </div>

            <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab"><h5>ประวัติผู้ใช้งาน</h5></a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">ชื่อ - นามสกุล</label>
                        <div class="col-sm-10">
                          <input v-model="form.name" type="text" class="form-control" id="name" placeholder="ชื่อ - นามสกุล" :class="{ 'is-invalid': form.errors.has('name') }">
                          <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">อีเมล</label>

                        <div class="col-sm-10">
                          <input v-model="form.email" type="email" class="form-control" id="email" placeholder="อีเมล" :class="{ 'is-invalid': form.errors.has('email') }">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="tel" class="col-sm-2 control-label">เบอร์โทรศัพท์</label>

                        <div class="col-sm-10">
                          <input v-model="form.tel" type="text" class="form-control" id="tel" placeholder="เบอร์โทรศัพท์">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="photo" class="col-sm-5 control-label">รูปโปรไฟล์ (ขนาดไฟล์ภาพต้องไม่เกิน 2 MB)</label>
                        <div class="col-sm-10">
                            <input type="file" @change="updateProfile" name="photo" class="form-input" >
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="password" class="col-sm-5 control-label">รหัสผ่าน (เว้นว่างไว้ หากไม่ต้องการเปลี่ยน)</label>

                        <div class="col-sm-10">
                          <input v-model="form.password" type="password" name="password" id="password" class="form-control"  placeholder="รหัสผ่าน" :class="{ 'is-invalid': form.errors.has('password') }">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button @click.prevent="updateInfo" type="submit" class="btn btn-primary">อัพเดท</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
          return {
            form: new Form({
              id:'',
              name: '',
              email: '',
              password: '',
              tel: '',
              photo: '',
              type: ''
            })
          }
        },
        mounted() {
            console.log('Component mounted.')
        },

        methods:{
          getProfilePhoto(){
            let photo = (this.form.photo.length > 200) ? this.form.photo : "img/profile/"+ this.form.photo ;
            return photo;
          },
          updateInfo(){
            this.$Progress.start();
            this.form.put('api/profile/')
            .then(() => {
                Fire.$emit('AfterCreate');
                swal({
                      type: 'success',
                      title: 'อัพเดท!',
                      text: 'ข้อมูลผู้ใช้ถูกอัพเดทเรียบร้อยแล้ว',
                  })
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
            });
          },
          updateProfile(e){
            //console.log('uploading');
              let file = e.target.files[0];
              console.log(file);
              let reader = new FileReader();

              if(file['size'] < 2111775){
                  reader.onloadend = (file) => {
                  // console.log('RESULT', reader.result)
                  this.form.photo = reader.result;
                  }
                  reader.readAsDataURL(file);
              }else{
                  swal({
                      type: 'error',
                      title: 'มีบางสิ่งผิดพลาด',
                      text: 'คุณอัพโหลดไฟล์รูปภาพขนาดใหญ่เกินไป',
                  })
              }
          }
        },
        created() {
            axios.get("api/profile")
            .then(({ data }) => (this.form.fill(data)));
        }
    }
</script>
