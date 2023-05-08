// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyCbfWqlcVN-hBVRpzXFZMZHyEUIvPZ2JLA",
  authDomain: "brahmdhamtirth-a3750.firebaseapp.com",
  projectId: "brahmdhamtirth-a3750",
  storageBucket: "brahmdhamtirth-a3750.appspot.com",
  messagingSenderId: "830887718936",
  appId: "1:830887718936:web:b48b10f68ac6c25a9ad36d",
  measurementId: "G-N3KMX0QW31"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);