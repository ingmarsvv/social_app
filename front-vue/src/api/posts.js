import axios from 'axios'

export async function getPosts(page) {
  const pageNr = page + 1
  const response = await axios.get('api/posts?page=' + pageNr);
  return response.data;
}

export async function getPost(id) {
  const response = await axios.get(`api/posts/${id}`);
  return response.data;
}

export async function createPost(form) {
  const response = await axios.post('/api/posts', form)
  return response.data
}

export async function updatePost(id, form) {
  const response = await axios.put(`api/posts/${id}`, form)
  return response.data;
}

export async function deletePost(id) {
  await axios.delete(`api/posts/${id}`);
}

export async function getUserPosts(page) {
  const pageNr = page + 1
  const response = await axios.get('/api/user/posts?page=' + pageNr)
  return response.data
}
