// File: @/lib/firebase.js
        
import 'firebase/firestore'
import firebase from 'firebase/app'

// More about firebase config on https://firebase.google.com/docs/web/setup#config-object
var firebaseConfig = {
  apiKey: process.env.API_KEY,
  authDomain: process.env.AUTH_DOMAIN,
  projectId: process.env.PROJECT_ID,
  storageBucket: process.env.STORAGE_BUCKET,
  messagingSenderId: process.env.MESSAGING_SENDER_ID,
  appId: process.env.APP_ID,
}

if (!firebase.apps.length) {
  firebase.initializeApp(firebaseConfig)
} else {
  firebase.app()
}
        
export const firestore = firebase.firestore()

export default firebase
