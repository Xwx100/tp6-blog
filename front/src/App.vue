<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>
  import jwt_decode from 'jwt-decode'
  export default {
    name:"App",
    created () { //持久化登录
      let userInfo
      if(userInfo = JSON.parse(localStorage.userInfo)) {
        //存储vuex中
        this.$store.dispatch('setAuthenticated' , !this.isEmpty(userInfo))
        this.$store.dispatch('setUSER', userInfo)
        console.log(userInfo)
      }
    },
    methods:{
      isEmpty(value) {
        return (
          value === undefined || value === null || (typeof value === 'object' && Object.keys(value).length === 0)
          || (typeof value === 'string' && value.trim().length === 0)
        )
      }
    }
  }
</script>


<style>
html,body,#app {
  width: 100%;
  height: 100%;
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  overflow: hidden;
}
</style>
