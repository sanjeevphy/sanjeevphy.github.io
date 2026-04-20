// ----------- SUBTOPIC DATA -----------

const subtopics = {

NPHP:[
"Advances in Nuclear Science",
"Advances in Plasma Physics",
"High Energy Physics & Cosmology"
],

AMNS:[
"Material Science & Applications",
"Biomaterials & Applications",
"Nanomaterials & Nanotechnology"
],

ESHT:[
"Electric Energy Storage",
"Thermal Energy Storage",
"Hydrogen Energy & Technology"
],

RSES:[
"Solar Thermal Technologies",
"Materials for Solar Cells",
"Trends in Wind and Biomass Energy"
],

EPIS:[
"Microwave Electronics & Communication",
"Photonics & Optical Devices",
"Quantum Computing & Artificial Intelligence"
],

EAS:[
"Environmental Remedies",
"Waste Management",
"Agrophysics & Technology"
]

};


// ----------- COUNTRY SELECTION -----------

document.getElementById("country").addEventListener("change",function(){

const otherBox = document.getElementById("otherCountryBox")

if(this.value === "Other"){
otherBox.classList.remove("hidden")
}else{
otherBox.classList.add("hidden")
}

})



// ----------- PARTICIPATION LOGIC -----------

document.getElementById("participation").addEventListener("change",function(){

const p = this.value

const trackBox = document.getElementById("trackBox")
const abstractBox = document.getElementById("abstractBox")
const travelBox = document.getElementById("travelBox")

const track = document.getElementById("track")
const subtopic = document.getElementById("subtopic")

if(p === "Poster" || p === "Present"){

trackBox.classList.remove("hidden")
abstractBox.classList.remove("hidden")
travelBox.classList.add("hidden")

track.required = true
subtopic.required = true

}

else if(p === "Invited Talk" || p === "Keynote Speaker"){

trackBox.classList.add("hidden")
abstractBox.classList.add("hidden")
travelBox.classList.remove("hidden")

track.required = false
subtopic.required = false

}

else{

trackBox.classList.add("hidden")
abstractBox.classList.add("hidden")
travelBox.classList.add("hidden")

track.required = false
subtopic.required = false

}

})



// ----------- TRACK -> SUBTOPIC -----------

document.getElementById("track").addEventListener("change",function(){

const topicSelect = document.getElementById("subtopic")

topicSelect.innerHTML = ""

subtopics[this.value].forEach(function(t){

const opt = document.createElement("option")

opt.value = t
opt.text = t

topicSelect.appendChild(opt)

})

})



// ----------- FORM SUBMISSION -----------

document.getElementById("registrationForm").addEventListener("submit",async function(e){

e.preventDefault()

const form = e.target

const formData = {

title: form.title.value,
name: form.name.value,
institute: form.institute.value,
country: form.country.value,
email: form.email.value,
altEmail: form.altEmail.value,
phone: form.phone.value,
participation: form.participation.value,
track: form.track.value,
subtopic: form.subtopic.value,
travel: form.travel ? form.travel.value : ""

}


// ----------- FILE VALIDATION -----------

const fileInput = document.getElementById("abstractFile")

if(fileInput.files.length > 0){

const file = fileInput.files[0]

if(!file.name.endsWith(".docx")){
alert("Only .docx files allowed")
return
}

if(file.size > 1000000){
alert("File must be less than 1 MB")
return
}

const base64 = await toBase64(file)

formData.fileData = base64
formData.fileName = file.name

}



// ----------- SEND DATA TO GOOGLE SCRIPT -----------

fetch("https://script.google.com/macros/s/AKfycbxf5IG0ODJyD92psi5WyGz4_4KpRaiTw6L3MzcDkdUdp6QS3tGd3urYqY59v-j8xIJa/exec",{

method:"POST",
body:JSON.stringify(formData)

})

.then(res => res.text())

.then(()=>{

redirectPayment(formData)

})

.catch(err=>{

alert("Submission error")
console.log(err)

})

})



// ----------- BASE64 FILE CONVERSION -----------

function toBase64(file){

return new Promise((resolve,reject)=>{

const reader = new FileReader()

reader.readAsDataURL(file)

reader.onload = () => resolve(reader.result)

reader.onerror = error => reject(error)

})

}



// ----------- PAYMENT REDIRECT -----------

function redirectPayment(data){

let amount = ""
let currency = ""

if(data.country === "India"){

currency = "INR"

if(data.participation === "Attend"){
amount = 1
}

if(data.participation === "Poster" || data.participation === "Present"){
amount = 2
}

}
else{

currency = "USD"

if(data.participation === "Attend"){
amount = 1
}

if(data.participation === "Poster" || data.participation === "Present"){
amount = 2
}

}


if(amount !== ""){

window.location.href =
"payment.html?amount="+amount+"&currency="+currency

}

}
