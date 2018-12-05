<template>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="height:450px;">
                        <h4 style="color:rgb(99, 106, 111);text-align:center;">ยินดีต้อนรับเข้าสู่ระบบ</h4>
                        <br>
                        <img class="img-fluid" src="img/siamcity.jpg" width="25%" height="" style="display: block;margin-left: auto;margin-right: auto;">
                        <h2 style="color:#0158a7;  text-shadow: 1px 1px #9ea9e4;text-align: center;">
                            ระบบบันทึกข้อมูลประกันภัยอุบัติเหตุนักเรียน
                        </h2>
                        <p style="color:#0158a7; text-align: center;">
                            สำนักงานตัวแทนประกันวินาศภัย บริษัท สยามซิตี้ประกันภัย จำกัด (มหาชน)
                        </p>
                    </div> 
                </div>
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
              type: ''
            })
          }
        },
        created() {
            axios.get("api/profile")
            .then(({ data }) => (this.form.fill(data)));
        }
    }
</script>
