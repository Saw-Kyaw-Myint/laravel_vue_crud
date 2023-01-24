<template>
  <div class="whole">
    <div class="">
  <button @click="toggleCreate()" id="create">Create</button>
  <div class="post-form" v-if="create">
    <span class="crop" @click="create=false">x</span>
    <p class="title">Post create</p>
    <form @submit.prevent="addNewPost" enctype="multipart/form-data"  @blur="create=false">
      <label for="">Title</label> <br>
      <input type="text" name="title" v-model="newPost" placeholder="title" /><br>
      <span class="error">{{ errors.errorTitle }}</span> <br>
      <label for="">Image</label><br>
      <input type="file" v-on:change="onChange" /><br>
      <sapn class="error">{{ errors.errorFile }}</sapn>  <br>
      <button type="submit">create</button>
    </form>
  </div>
  <table class="" id="customers">
    <thead>
      <tr>
        <th>id</th>
        <th>title</th>
        <th>image</th>
        <th>action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="post in posts" :key="post.id">
        <td>{{ post.id }}</td>
        <td>{{ post.title }}</td>
        <td>
          <img v-bind:src="'http://127.0.0.1:8000' + post.image_path" alt="fff" class="image" />
        </td>

        <td>
          <button @click="toggleEdit(post.id)" id="edit">edit</button>
          <button id="delete" @click="deletePost(post.id)">delete</button>
          <div class="update-form" v-if="edit">
            <span class="crop"  @click="edit=crop">x</span>
            <p class="title">Post edit</p>
            <form @submit.prevent="updatePost(post.id, post.title)" enctype="multipart/form-data">
              <label for="">title</label>
              <input type="text" name="title" v-model="post.title" /> <br>
              <span class="error">{{ errors.errorTitle }}</span> <br>
              <label for="">image</label>
              <input type="file" v-on:change="onChange" /> <br>
               <span class="error">{{ errors.errorTitle }}</span> <br>
              <button type="submit">submit</button>
            </form>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
    </div>
</div>
</template>
<script setup>
import { ref,onMounted,reactive} from "vue";
    const newPost = ref("");
    const posts = ref([]);
    const file = ref("");
    const updateFile = ref("");
    const edit = ref(false);
    const create = ref(false);
    const errors = reactive({
            errorTitle: '',
            errorFile: ''
        })

    onMounted(() => {
      getPost();
    });

    function toggleEdit() {
      edit.value = !edit.value;
      errors.errorTitle='';
      errors.errorFile='';
    }

    function toggleCreate() {
      create.value = !create.value;
    }
    const onChange = (e) => {
      file.value = e.target.files[0];
    };

    // post methods
    function addNewPost(e) {
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };
      let data = new FormData();
      data.append("file", file.value);
      data.append("title", newPost.value);


      axios
        .post("api/post", data, config)
        .then((response) => {
          alert(response.data.message);
          newPost.value = "";
          getPost();
          create.value=false;
        })
        .catch(function (error) {
          errors.errorTitle = error.response.data.errors.title[0];
          errors.errorFile = error.response.data.errors.file[0];
        });
    }

    //get methods
    function getPost() {
      axios
        .get("api/post")
        .then((response) => {
          posts.value = response.data;
        })
        .catch((error) => {
          consol.log(error);
          posts.value = [];
        });
    }

    function updatePost(id,updateText) {
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };
      updateFile.value=file.value;
      let data = new FormData();
      data.append("file", updateFile.value);
      data.append("title", updateText);
      console.log(data.title);
      axios
        .post(`api/post/update/${id}`, data, config)
        .then((response) => {
          alert(response.data.message);
          newPost.value = "";
          edit.value=false;
          getPost();
        })
        .catch(function (error) {
         errors.errorTitle = error.response.data.errors.title[0];
        errors.errorTitle = error.response.data.errors.file[0];
        });
    }


    //delete Post
    function deletePost(id) {
      axios.delete(`api/post/${id}`).then((response) => {
        confirm("are you sure to delete");
        getPost();
      });
    }

</script>
<style  scoped>
.image {
  width: 100px;
  height: 100px;
}
.title{
  font-size: 20px;
  text-align: center;
}
.whole{
width: 1000px;
margin-top: 200px !important;
margin: 0 auto;
}
#create {
  border: none;
  background-color: #38278d;
  color: #fff;
  font-weight: bold;
  margin-bottom: 10px;
  border-radius: 10px;
  padding: 10px;
}

#edit {
  border: none;
  background-color: #00772b;
  color: #fff;
  font-weight: bold;
  margin-right: 10px;
  border-radius: 10px;
  padding: 10px;
}

#delete {
  border: none;
  background-color: #e30f0f;
  color: #fff;
  font-weight: bold;
  border-radius: 10px;
  padding: 10px;
}
.crop{
  padding: 3px 8px;
    border-radius: 50%;
    background-color: #e30f0f;
    color: #fff;
    position: absolute;
    right: 0;
    top: 0;
}
.post-form {
  padding: 20px;
  position: absolute;
  top: 100px;
  left: 35%;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
  background-color: rgb(239, 239, 239);
  width: 300px;
  border-radius: 20px;
}

.post-form input {
  margin-bottom: 10px;
  padding: 5px 3px;
  width: 100%;
}

#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td,
#customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even) {
  background-color: #f2f2f2;
}

#customers tr:hover {
  background-color: #ddd;
}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

.update-form {
  position: absolute;
  background-color: #f6f6f6;
  top: 100px;
  width: 300px;
  padding: 5px 3px;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
  padding: 10px;
  left: 35%;
  border-radius: 10px;
}

label{
  display: inline-block;
 margin-bottom: 10px;
}

.update-form input {
  margin-bottom: 10px;
  padding: 5px 3px;
  width: 98%;
}
.error{
  color: red;
}
</style>
