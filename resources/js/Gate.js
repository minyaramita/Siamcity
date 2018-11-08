export default class Gate{

    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.type === 'ผู้ดูแลระบบ';
    }

    isUser(){
        return this.user.type === 'ผู้ใช้งาน';
    }

}

