// File: @/components/blog/comments.js
      
import { useState } from 'react'
import firebase, { firestore } from '@/lib/firebase'
export const getComments = (slug, callBackFunction) => {
  firestore
    .collection('comments')
    .get()
    .then((snapshot) => {
      const posts = snapshot.docs
        .map((doc) => doc.data())
        .filter((doc) => doc.slug === slug)
        .map((doc) => {
          return { id: doc.id, ...doc }
        })
      callBackFunction(posts)
    })
    .catch((err) => {
      console.log(err)
    })
}
export const writeComment = (name, slug, content, email, callBackFunction) => {
  let temp = {
    name,
    slug,
    content,
    time: firebase.firestore.Timestamp.fromDate(new Date()),
  }
  if (email.length > 0) temp['email'] = email
  firestore
    .collection('comments')
    .add(temp)
    .then(() => {
      callBackFunction()
    })
    .catch((err) => {
      console.error(err)
    })
}
export const LoadComments = ({ comments }) => {
  return comments
    .sort((a, b) =>
      a.time.toDate().getTime() > b.time.toDate().getTime() ? -1 : 1
    )
    .map((item) => (
      <div
        key={item.time.seconds}
        className="border dark:border-gray-500 rounded p-5 w-full mt-5 flex flex-col"
      >
        <span className="text-lg text-gray-500 dark:text-gray-300 font-medium">
          {item.name} &middot; {item.time.toDate().toDateString()}
        </span>
        <span className="mt-3 text-md text-gray-500 dark:text-gray-300">
          {item.content}
        </span>
      </div>
    ))
}
const WriteComment = ({ slug, setComments }) => {
  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [comment, setComment] = useState('')

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault()
        writeComment(name, slug, comment, email, () =>
          getComments(slug, setComments)
        )
        setName('')
        setEmail('')
        setComment('')
      }}
      className="mt-10 flex flex-col w-full"
    >
      <h1 className="font-semibold text-lg">Write a comment</h1>
      <div className="flex flex-col sm:flex-row sm:space-x-5 items-start">
        <input
          required
          value={name}
          placeholder="Name*"
          onChange={(e) => setName(e.target.value)}
          className="mt-5 w-full sm:w-1/2 appearance-none outline-none ring-0 px-5 py-2 border dark:hover:border-white hover:border-black rounded hover:shadow text-black dark:bg-black dark:text-gray-300 dark:border-gray-500"
        />
        <div className="mt-5 w-full sm:w-1/2 flex flex-col space-y-1">
          <input
            value={email}
            placeholder="Email (Optional)"
            onChange={(e) => setEmail(e.target.value)}
            className="w-full appearance-none outline-none ring-0 px-5 py-2 border dark:hover:border-white hover:border-black rounded hover:shadow text-black dark:bg-black dark:text-gray-300 dark:border-gray-500"
          />
          <span className="text-sm text-gray-400">
            Email will remain confidential.
          </span>
        </div>
      </div>
      <textarea
        required
        value={comment}
        onChange={(e) => setComment(e.target.value)}
        placeholder={'Comment*\nMaximum of 500 characters.'}
        className="mt-5 appearance-none outline-none ring-0 pt-5 px-5 pb-10 border dark:hover:border-white hover:border-black rounded hover:shadow text-black dark:bg-black dark:text-gray-300 dark:border-gray-500"
      />
      <button
        type="submit"
        className="w-[200px] appearance-none mt-5 py-2 px-5 text-center rounded border hover:bg-gray-100 dark:hover:bg-[#28282B] dark:border-gray-500"
      >
        Post a comment
      </button>
    </form>
  )
}

export default WriteComment
import WriteComment, { getComments, LoadComments } from '@/components/blog/comments'

export default function Post({ post }) {
  const [comments, setComments] = useState([])
  return <>
    <WriteComment setComments={setComments} slug={post.slug} />
    <div className="mt-10 pt-10 w-full border-t dark:border-gray-500">
      <button
        onClick={() => getComments(post.slug, setComments)}
        className="w-[200px] appearance-none py-2 px-5 text-center rounded border hover:bg-gray-100 dark:hover:bg-[#28282B]   dark:border-gray-500"
      >
        Load Comments
      </button>
    </div>
    <LoadComments comments={comments} />
  </>
}
