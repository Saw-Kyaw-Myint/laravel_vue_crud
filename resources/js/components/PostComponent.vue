<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Create new post</div>
          <div class="card-body">
            <p id="success"></p>
            <form @submit.prevent="addNewPost" enctype="multipart/form-data">
              <input type="text" name="title" v-model="newPost" />
              <input type="file" v-on:change="onChange" />
              <input type="submit" value="Submit" />
            </form>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>id</th>
                  <th>title</th>
                  <th>description</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="post in posts" :key="post.id">
                  <td>{{ post.id }}</td>
                  <td @dblclick="deletePost(post.id)">{{ post.title }}</td>
                  <td>
                    <img
                      v-bind:src="'http://127.0.0.1:8000' + post.image_path"
                      alt="fff"
                      class="image"
                    />
                  </td>
                  <td>
                    <form
                      @submit.prevent="updatePost(post.id, post.title)"
                      enctype="multipart/form-data"
                    >
                      <input type="text" v-model="post.title" />
                      <input type="file" v-on:change="updateChange" />
                      <button type="submit">submit</button>
                    </form>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { ref, onMounted } from "vue";

export default {
  setup() {
    const newPost = ref("");
    const posts = ref([]);
    const file = ref("");
    const updateFile=ref("");

    onMounted(() => {
      getPost();
    });

    const onChange = (e) => {
      file.value = e.target.files[0];
    };

    // post methods
    function addNewPost(e) {
      e.preventDefault();
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };
      let data = new FormData();
      data.append("file", file.value);
      data.append("title", newPost.value);
      console.log(data.title);

      axios
        .post("/post", data, config)
        .then((response) => {
          alert(response.data.message);
          newPost.value = "";
          getPost();
        })
        .catch(function (err) {
          console.log("error");
        });
    }

    //get methods
    function getPost() {
      axios
        .get("/post")
        .then((response) => {
          posts.value = response.data;
          console.log(response.data);
        })
        .catch((error) => {
          consol.log(error);
          posts.value = [];
        });
    }

    //updatet Post
    const updateChange = (e) => {
      updateFile.value = e.target.files[0];
    };
    
    function updatePost(id, updateText) {
        alert(updateText)
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };
      let data = new FormData();
      data.append("file", updateFile.value);
      data.append("title", updateText);
      console.log(data.title);

      axios
        .put(`/post/update/${id}`,{data, config})
        .then((response) => {
          alert(response.data.message);
          newPost.value = "";
          getPost();
        })
        .catch(function (err) {
          console.log("error");
        });
//
//      axios
//        .put(`/post/edit/${id}`, {
//          title: updateText,
//        })
//        .then((res) => {
//          console.log(res);
//        })
//        .catch((error) => {
//          console.log("error");
//        });
    }

    //delete Post
    function deletePost(id) {
      axios.delete(`/post/${id}`).then((response) => {
        confirm("are you sure to delete");
        getPost();
      });
    }

    return {
      newPost,
      updatePost,
      updateChange,
      updateFile,
      onChange,
      posts,
      deletePost,
      getPost,
      addNewPost,
    };
  },
};
</script>
<style >
.image {
  width: 100px;
  height: 100px;
}
</style>
