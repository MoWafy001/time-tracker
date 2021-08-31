const colors = ["#f00", "#000", "#21ce6d", "#05f"];
let c = 0;
var tags = [];

function addTimeSpan(title, start, end, c, w){
    //if(end-start<0.5){end+=0.5};
    const div = document.createElement('div');
    div.classList.add('spent');
    div.style.cssText = `top:${(start/12)*100}%; height:${(end/12)*100-(start/12)*100}%; background: ${c};`;

    const tit = document.createElement('span');
    tit.textContent = title;
    tit.classList.add('span-title')
    tit.style.cssText = `color:${c};`;
    div.append(tit);

    const st = document.createElement('span');
    const et = document.createElement('span');
    if(Math.floor(start)==0){start=12;}
    const a = ["AM", "PM"];
    const sm = Math.round((start-Math.floor(start))*60);

    let sh = Math.floor(start)
    if(sh == 0) sh = 12;
    if (sm==0) {
        st.textContent = `${sh} ${a[w]}`;   
    }else{
        st.textContent = `${sh}:${sm} ${a[w]}`;
    }
    const em = Math.round((end-Math.floor(end))*60);

    let eh = Math.floor(end)
    if(eh == 0) eh = 12;
    if (em==0) {
        et.textContent = `${eh} ${a[((Math.floor(end)!=12)?w:Math.abs(1-w))]}`; 
    }else{
        et.textContent = `${eh}:${em} ${a[((Math.floor(end)!=12)?w:Math.abs(1-w))]}`;
    }
    st.classList.add("show-time");
    et.classList.add("show-time")
    st.style.cssText = `color:${c}; top:-5px; right: 200%;`;
    et.style.cssText = `color:${c}; bottom:-5px; left:200%;`;
    div.append(st);
    div.append(et);

    document.querySelectorAll(".tw")[w].append(div);
}

const renderTags = ()=>{
    const frag = document.createDocumentFragment();
    tags.forEach(title=>{
        const tag = document.createElement('div');
        tag.className = 'tag';
        if (title == currTask) {
            tag.style.cssText = "background: rgb(27, 172, 53); color:#fff; box-shadow: 0 0 5px 0 #fff;";
            tag.onclick = ()=>{stopTask(tag.querySelector('.tag-title').textContent);}
        }else
            tag.onclick = ()=>{updateCurrentTask(tag.querySelector('.tag-title').textContent);}
        tag.innerHTML = `
            <span class="tag-title">${title}</span>
        `;
        frag.append(tag);
    })
    document.querySelector(".tags").innerHTML = "";
    document.querySelector(".tags").append(frag);
}

function addTime(title, start, end, c){
    if ( (start<12 && end<12) || (start>=12 && end>=12) ) {
        w = (start>=12)?1:0;
        addTimeSpan(title, start-12*w, end-12*w, c, w);
    }else{
        addTimeSpan(title, start, 12, c, 0);
        addTimeSpan(title, 0, end-12, c, 1);
    }
}
async function postData(url = '', data = {}) {
    const d = new FormData()
    for(const k in data){
        d.append(k, data[k]);
    }
    const response = await fetch(url, {
        method: 'POST',
        body: d
    });
    
    return response.json();
}

async function update(i=0, updateInput = false){
    if (i<0) {
        i=0;
        d=i; 
    }
    currTime = new Date();
    currTime.setHours(0+currTime.getTimezoneOffset()/-60); currTime.setMinutes(0); currTime.setSeconds(0);
    currTime = currTime.toISOString().slice(0, 19).replace('T', ' ');
    if (!updateInput) {
        document.querySelector('h2').textContent = "loading...";
        tags=[]
    }
    await fetch("getData.php?prevDay="+i+"&curdate="+currTime).then(res=>{
        return res.json()
    }).then(data=>{
        document.querySelector("#time .main").innerHTML=
        `
        <div class="tw">
            <h5 class="top">12 AM</h5>
            <h5 class="bottom">12 PM</h5>
        </div>
        <div class="tw">
            <h5 class="top">12 PM</h5>
            <h5 class="bottom">12 AM</h5>
        </div>
        `;
        c = 0;
        // if (data.length != 0) {
        //     let s = data.sort((a,b)=> (new Date(a[2]))-(new Date(b[2])) )[0][2];
        //     s = new Date(s);
        //     if (s.getHours()+s.getMinutes()/60 > 0) {
        //         addTime("something from yesterday", 0, s.getHours()+s.getMinutes()/60, "#00f4");
        //     }
        // }
        let today = new Date();
        today.setHours(today.getHours()-24*i);
        data.forEach(arr => {
            const title = arr[1];
            if (!tags.includes(title)) {
                tags.push(title);
            }
            let start = arr[2];
            let end = arr[3];
            start = new Date(start);
            if (end) {
                end = new Date(end);
            }else{
                end = new Date();
                if (!updateInput) {  
                    currTask = title;
                    document.querySelector("#rightnow").value = title;    
                }
            }   
            c++;
            if (c>colors.length-1) {
                c=0;
            }
            if (start.getDay()!=end.getDay()) {
                if (today.getDay()!=start.getDay() &&today.getDay()!=end.getDay()) {
                    addTime(title, 0, 24, colors[c]);
                }else if ((today-start)/(60*60*24*1000)>0) {
                    addTime(title, 0, end.getHours()+end.getMinutes()/60, colors[c]);
                }else{
                    addTime(title, start.getHours()+start.getMinutes()/60, 24, colors[c]);
                }
            }else{
                addTime(title, start.getHours()+start.getMinutes()/60, end.getHours()+end.getMinutes()/60, colors[c]);
            }
        });
        document.querySelector('h2').textContent = today.toDateString();
        renderTags();
    });
}
update(0);

updateCurrentTask = (title)=>{
    if (title != currTask && title != "") {
        d = 0;
        if (!tags.includes(title)) {
            tags.push(title);
        }
        currTask = title;
        document.querySelector("#rightnow").value = title;
        let currTime = new Date();
        currTime.setHours(currTime.getHours()+currTime.getTimezoneOffset()/-60)
        currTime = currTime.toISOString().slice(0, 19).replace('T', ' ');
        if (title != 0 & title != null) {
            postData("updateTime.php", {title:title, currTime:currTime}).then(()=>{update();});   
        }
    }
    update(d)
};

stopTask = (title)=>{
    let currTime = new Date();
    currTime.setHours(currTime.getHours()+currTime.getTimezoneOffset()/-60)
    currTime = currTime.toISOString().slice(0, 19).replace('T', ' ');
    if (title != 0 & title != null) {
        currTask = ""
        postData("stopTask.php", {title:title, currTime:currTime}).then(()=>{update();});   
    }
};



var t = setInterval(()=>{update(d, true);},10000);