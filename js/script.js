const state = {
  currentView: 'profile',
  profile: {
    name:'ผศ.ดร.สมชาย ใจดี', email:'somchai.j@university.ac.th',
    position:'ผู้ช่วยศาสตราจารย์', department:'คณะวิทยาศาสตร์'
  },
  respInstructors: [
    {id:1, name:'ผศ.ดร.สมชาย ใจดี', position:'ผู้ช่วยศาสตราจารย์', curriculum:'วิทยาการคอมพิวเตอร์'},
    {id:2, name:'รศ.ดร.สุนีย์ พงษ์ไทย', position:'รองศาสตราจารย์', curriculum:'เทคโนโลยีสารสนเทศ'},
    {id:3, name:'ดร.วิภา ศรีสุข', position:'อาจารย์', curriculum:'วิทยาการคอมพิวเตอร์'}
  ],
  regInstructors: [
    {id:1, name:'อาจารย์ธนกร มั่งมี', position:'อาจารย์', curriculum:'วิทยาการคอมพิวเตอร์'},
    {id:2, name:'ผศ.ปิยะดา แสงทอง', position:'ผู้ช่วยศาสตราจารย์', curriculum:'เทคโนโลยีสารสนเทศ'},
    {id:3, name:'ดร.กิตติศักดิ์ บุญมา', position:'อาจารย์', curriculum:'วิศวกรรมซอฟต์แวร์'},
    {id:4, name:'รศ.ดร.มณีรัตน์ คงเพชร', position:'รองศาสตราจารย์', curriculum:'วิทยาการคอมพิวเตอร์'}
  ],
  courses: [
    {id:1, code:'CS101', name:'การเขียนโปรแกรมเบื้องต้น', credits:3, curriculum:'วิทยาการคอมพิวเตอร์'},
    {id:2, code:'CS205', name:'โครงสร้างข้อมูลและอัลกอริทึม', credits:3, curriculum:'วิทยาการคอมพิวเตอร์'},
    {id:3, code:'IT150', name:'ระบบฐานข้อมูล', credits:3, curriculum:'เทคโนโลยีสารสนเทศ'},
    {id:4, code:'SE220', name:'วิศวกรรมซอฟต์แวร์เบื้องต้น', credits:3, curriculum:'วิศวกรรมซอฟต์แวร์'},
    {id:5, code:'CS310', name:'ปัญญาประดิษฐ์เบื้องต้น', credits:3, curriculum:'วิทยาการคอมพิวเตอร์'}
  ],
  years: [
    {id:1, year:'2566'}, {id:2, year:'2567'}, {id:3, year:'2568'}
  ],
  semesters: [
    {id:1, name:'ภาคเรียนที่ 1', year:'2567'},
    {id:2, name:'ภาคเรียนที่ 2', year:'2567'},
    {id:3, name:'ภาคฤดูร้อน', year:'2567'}
  ],
  plans: [
    {id:1, name:'แผนการศึกษา 4 ปี ปกติ', curriculum:'วิทยาการคอมพิวเตอร์', year:'2567'},
    {id:2, name:'แผนการศึกษา 4 ปี เทียบโอน', curriculum:'เทคโนโลยีสารสนเทศ', year:'2567'}
  ],
  files: [
    {id:1, name:'มคอ.2-วิทยาการคอมพิวเตอร์-2567.pdf', size:'1.8 MB', date:'12 ก.ค. 2569'}
  ],
  departments: [
    {name:'คณะวิทยาศาสตร์', curricula:['วิทยาการคอมพิวเตอร์','เทคโนโลยีสารสนเทศ']},
    {name:'คณะวิศวกรรมศาสตร์', curricula:['วิศวกรรมซอฟต์แวร์']}
  ],
  messages: [
    {id:1, name:'นายเอกชัย รักเรียน', email:'ekkachai@example.com', subject:'สอบถามเรื่องการเทียบโอนหน่วยกิต', body:'อยากทราบขั้นตอนและเอกสารที่ต้องใช้ในการเทียบโอนหน่วยกิตจากสถาบันเดิมครับ', date:'10 ก.ค. 2569', read:false},
    {id:2, name:'นางสาวพิมพ์ชนก ดวงใจ', email:'pimchanok@example.com', subject:'สอบถามแผนการศึกษาหลักสูตร IT', body:'พอดีสนใจหลักสูตรเทคโนโลยีสารสนเทศ อยากทราบว่ามีแผนเรียนภาคพิเศษไหมคะ', date:'8 ก.ค. 2569', read:true},
    {id:3, name:'นายภูริช แสงอรุณ', email:'phurich@example.com', subject:'ปัญหาการอัพโหลดเอกสารสมัคร', body:'ระบบแจ้งไฟล์เกินขนาดครับ ต้องการทราบขนาดไฟล์สูงสุดที่ระบบรองรับ', date:'3 ก.ค. 2569', read:false}
  ]
};
let nextId = 1000;

/* ============================================================
   HELPERS
   ============================================================ */
function el(html){ const t=document.createElement('template'); t.innerHTML=html.trim(); return t.content.firstChild; }
function esc(s){ return String(s).replace(/[&<>"]/g, c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c])); }
function toast(msg){
  const t=document.getElementById('toast');
  document.getElementById('toast-text').textContent=msg;
  t.classList.add('show');
  clearTimeout(toast._tm);
  toast._tm=setTimeout(()=>t.classList.remove('show'),2400);
}
function initials(name){
  const parts=name.replace(/^(ผศ\.|รศ\.|ศ\.|ดร\.|อาจารย์)\s*/g,'').trim().split(' ');
  return (parts[0]?parts[0][0]:'') + (parts[1]?parts[1][0]:'');
}

/* ============================================================
   MODAL
   ============================================================ */
function openModal({title, sub, fields, values={}, onSubmit, submitLabel='บันทึก'}){
  const overlay=document.getElementById('modal-overlay');
  const box=document.getElementById('modal-box');
  box.innerHTML='';
  const form=el(`<form></form>`);
  form.appendChild(el(`<h3>${esc(title)}</h3>`));
  if(sub) form.appendChild(el(`<p class="modal-sub">${esc(sub)}</p>`));

  fields.forEach(f=>{
    const wrap=el(`<div class="field"></div>`);
    wrap.appendChild(el(`<label>${esc(f.label)}</label>`));
    let input;
    if(f.type==='select'){
      input=el(`<select name="${f.key}"></select>`);
      f.options.forEach(opt=>{
        const o=el(`<option value="${esc(opt)}">${esc(opt)}</option>`);
        if(values[f.key]===opt) o.selected=true;
        input.appendChild(o);
      });
    } else {
      input=el(`<input name="${f.key}" type="${f.type||'text'}" ${f.min!==undefined?`min="${f.min}"`:''}>`);
      input.value = values[f.key] ?? '';
      if(f.placeholder) input.placeholder=f.placeholder;
    }
    input.required = f.required !== false;
    wrap.appendChild(input);
    form.appendChild(wrap);
  });

  const actions=el(`<div class="modal-actions">
      <button type="button" class="btn btn-outline" id="modal-cancel">ยกเลิก</button>
      <button type="submit" class="btn btn-brass">${esc(submitLabel)}</button>
    </div>`);
  form.appendChild(actions);
  box.appendChild(form);
  overlay.style.display='flex';

  form.querySelector('#modal-cancel').onclick=closeModal;
  form.onsubmit=(e)=>{
    e.preventDefault();
    const data={};
    fields.forEach(f=>{ data[f.key]=form.querySelector(`[name="${f.key}"]`).value; });
    onSubmit(data);
    closeModal();
  };
  setTimeout(()=>{ const first=form.querySelector('input,select'); if(first) first.focus(); },30);
}
function closeModal(){ document.getElementById('modal-overlay').style.display='none'; }
document.getElementById('modal-overlay').addEventListener('click', e=>{
  if(e.target.id==='modal-overlay') closeModal();
});
document.addEventListener('keydown', e=>{ if(e.key==='Escape') closeModal(); });

function confirmDelete(label, onYes){
  openModal({
    title:'ยืนยันการลบข้อมูล',
    sub:`ต้องการลบ "${label}" ใช่หรือไม่? การลบข้อมูลไม่สามารถย้อนกลับได้`,
    fields:[],
    submitLabel:'ลบข้อมูล',
    onSubmit:()=>onYes()
  });
  setTimeout(()=>{ const btn=document.querySelector('#modal-box button[type=submit]'); if(btn){ btn.classList.remove('btn-brass'); btn.classList.add('btn-danger'); btn.style.background=''; btn.style.border='1px solid #B54B3A'; btn.style.color='#B54B3A'; } },10);
}

/* ============================================================
   ROUTER
   ============================================================ */
const VIEW_META = {
  'profile':{crumb:'แก้ไขข้อมูลส่วนตัว'},
  'resp-instructors':{crumb:'อาจารย์ผู้รับผิดชอบหลักสูตร'},
  'reg-instructors':{crumb:'อาจารย์ประจำหลักสูตร'},
  'courses':{crumb:'รายวิชา'},
  'upload':{crumb:'อัพโหลดไฟล์หลักสูตร'},
  'years':{crumb:'ปีการศึกษา'},
  'semesters':{crumb:'ภาคการศึกษา'},
  'plans':{crumb:'แผนการศึกษา'},
  'search-dept':{crumb:'ค้นหาตามสาขา'},
  'search-curr':{crumb:'ค้นหาตามหลักสูตร'},
  'messages':{crumb:'ข้อความติดต่อสอบถาม'}
};

function navigate(view){
  state.currentView=view;
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.toggle('active', n.dataset.view===view));
  document.getElementById('crumb-current').textContent=VIEW_META[view].crumb;
  document.getElementById('sidebar').classList.remove('open');
  render();
}
document.querySelectorAll('.nav-item').forEach(item=>{
  item.addEventListener('click', ()=>navigate(item.dataset.view));
});

function render(){
  const c=document.getElementById('content');
  c.innerHTML='';
  const map={
    'profile':renderProfile,
    'resp-instructors':()=>renderInstructors('respInstructors','อาจารย์ผู้รับผิดชอบหลักสูตร','1.3.2.3','เพิ่มอาจารย์ผู้รับผิดชอบหลักสูตร'),
    'reg-instructors':()=>renderInstructors('regInstructors','อาจารย์ประจำหลักสูตร','1.3.2.4','เพิ่มอาจารย์ประจำหลักสูตร'),
    'courses':renderCourses,
    'upload':renderUpload,
    'years':renderYears,
    'semesters':renderSemesters,
    'plans':renderPlans,
    'search-dept':renderSearchDept,
    'search-curr':renderSearchCurr,
    'messages':renderMessages
  };
  c.appendChild(map[state.currentView]());
}

/* ============================================================
   PAGE HEAD helper
   ============================================================ */
function pageHead(ref, title, desc){
  return el(`<div class="page-head">
    <div class="eyebrow">ข้อกำหนด ${ref}</div>
    <h2>${esc(title)}</h2>
    <p>${esc(desc)}</p>
  </div>`);
}

/* ============================================================
   1.3.2.2 PROFILE
   ============================================================ */
function renderProfile(){
  const wrap=el(`<div></div>`);
  wrap.appendChild(pageHead('1.3.2.2','แก้ไขข้อมูลส่วนตัว','จัดการชื่อ ตำแหน่งทางวิชาการ และข้อมูลติดต่อของบัญชีผู้ใช้ของคุณ'));
  const card=el(`<div class="card" style="max-width:560px;"></div>`);
  const form=el(`<form></form>`);
  const p=state.profile;
  form.innerHTML=`
    <div class="field"><label>ชื่อ-นามสกุล</label><input name="name" value="${esc(p.name)}" required></div>
    <div class="field"><label>อีเมล</label><input name="email" type="email" value="${esc(p.email)}" required></div>
    <div class="field-row">
      <div class="field"><label>ตำแหน่งทางวิชาการ</label>
        <select name="position">${POSITIONS.map(pos=>`<option ${pos===p.position?'selected':''}>${esc(pos)}</option>`).join('')}</select>
      </div>
      <div class="field"><label>สังกัด</label><input name="department" value="${esc(p.department)}" required></div>
    </div>
    <button class="btn btn-brass" type="submit">บันทึกการเปลี่ยนแปลง</button>
  `;
  form.onsubmit=(e)=>{
    e.preventDefault();
    p.name=form.name.value; p.email=form.email.value; p.position=form.position.value; p.department=form.department.value;
    document.getElementById('user-name').textContent=p.name;
    document.getElementById('user-avatar').textContent=initials(p.name);
    toast('บันทึกข้อมูลส่วนตัวเรียบร้อยแล้ว');
  };
  card.appendChild(form);
  wrap.appendChild(card);
  return wrap;
}

/* ============================================================
   1.3.2.3 / 1.3.2.4 / 1.3.2.5 INSTRUCTORS (+ position edit inline)
   ============================================================ */
function renderInstructors(stateKey, title, ref, addLabel){
  const wrap=el(`<div></div>`);
  wrap.appendChild(pageHead(ref, title, 'เพิ่ม ลบ แก้ไขรายชื่อและตำแหน่งทางวิชาการของอาจารย์ในหลักสูตร (1.3.2.5)'));
  const card=el(`<div class="card"></div>`);
  const head=el(`<div class="card-head">
      <h3>รายชื่ออาจารย์ (${state[stateKey].length})</h3>
      <button class="btn btn-brass">+ ${esc(addLabel)}</button>
    </div>`);
  head.querySelector('button').onclick=()=>openInstructorModal(stateKey, null, title);
  card.appendChild(head);

  if(state[stateKey].length===0){
    card.appendChild(el(`<div class="empty-state"><div class="glyph">🗂</div><p>ยังไม่มีรายชื่ออาจารย์ กดปุ่มด้านบนเพื่อเพิ่มรายชื่อแรก</p></div>`));
  } else {
    const table=el(`<table>
      <thead><tr><th>ชื่อ-นามสกุล</th><th>ตำแหน่งทางวิชาการ</th><th>หลักสูตร</th><th></th></tr></thead>
      <tbody></tbody>
    </table>`);
    const tbody=table.querySelector('tbody');
    state[stateKey].forEach(ins=>{
      const tr=el(`<tr>
        <td style="font-weight:600;">${esc(ins.name)}</td>
        <td><span class="tag brass">${esc(ins.position)}</span></td>
        <td>${esc(ins.curriculum)}</td>
        <td><div class="row-actions">
          <button class="icon-btn" title="แก้ไข">✎</button>
          <button class="icon-btn danger" title="ลบ">🗑</button>
        </div></td>
      </tr>`);
      tr.querySelectorAll('button')[0].onclick=()=>openInstructorModal(stateKey, ins, title);
      tr.querySelectorAll('button')[1].onclick=()=>{
        confirmDelete(ins.name, ()=>{
          state[stateKey]=state[stateKey].filter(x=>x.id!==ins.id);
          toast('ลบข้อมูลอาจารย์เรียบร้อยแล้ว');
          render();
        });
      };
      tbody.appendChild(tr);
    });
    card.appendChild(table);
  }
  wrap.appendChild(card);
  return wrap;
}
function openInstructorModal(stateKey, ins, title){
  openModal({
    title: ins? `แก้ไขข้อมูลอาจารย์` : title,
    sub: ins? 'แก้ไขชื่อ ตำแหน่งทางวิชาการ หรือหลักสูตรที่สังกัด' : 'กรอกข้อมูลอาจารย์ที่ต้องการเพิ่มเข้าในหลักสูตร',
    fields:[
      {key:'name', label:'ชื่อ-นามสกุล', placeholder:'เช่น ผศ.ดร.สมชาย ใจดี'},
      {key:'position', label:'ตำแหน่งทางวิชาการ', type:'select', options:POSITIONS},
      {key:'curriculum', label:'หลักสูตร', type:'select', options:CURRICULA}
    ],
    values: ins || {},
    submitLabel: ins? 'บันทึกการแก้ไข' : 'เพิ่มรายชื่อ',
    onSubmit:(data)=>{
      if(ins){ Object.assign(ins, data); toast('แก้ไขข้อมูลอาจารย์เรียบร้อยแล้ว'); }
      else{ state[stateKey].push({id:nextId++, ...data}); toast('เพิ่มรายชื่ออาจารย์เรียบร้อยแล้ว'); }
      render();
    }
  });
}

/* ============================================================
   1.3.2.6-8 COURSES
   ============================================================ */
function renderCourses(){
  const wrap=el(`<div></div>`);
  wrap.appendChild(pageHead('1.3.2.6 - 1.3.2.8','รายวิชา','เพิ่ม ลบ แก้ไขรหัสวิชา ชื่อวิชา และจำนวนหน่วยกิตของแต่ละรายวิชาในหลักสูตร'));
  const card=el(`<div class="card"></div>`);
  const head=el(`<div class="card-head"><h3>รายวิชาทั้งหมด (${state.courses.length})</h3><button class="btn btn-brass">+ เพิ่มรายวิชา</button></div>`);
  head.querySelector('button').onclick=()=>openCourseModal(null);
  card.appendChild(head);
  const table=el(`<table>
    <thead><tr><th>รหัสวิชา</th><th>ชื่อวิชา</th><th>หน่วยกิต</th><th>หลักสูตร</th><th></th></tr></thead>
    <tbody></tbody></table>`);
  const tbody=table.querySelector('tbody');
  state.courses.forEach(cs=>{
    const tr=el(`<tr>
      <td><span class="stamp">${esc(cs.code)}</span></td>
      <td style="font-weight:600;">${esc(cs.name)}</td>
      <td class="mono">${cs.credits}</td>
      <td>${esc(cs.curriculum)}</td>
      <td><div class="row-actions">
        <button class="icon-btn" title="แก้ไข">✎</button>
        <button class="icon-btn danger" title="ลบ">🗑</button>
      </div></td>
    </tr>`);
    tr.querySelectorAll('button')[0].onclick=()=>openCourseModal(cs);
    tr.querySelectorAll('button')[1].onclick=()=>{
      confirmDelete(`${cs.code} ${cs.name}`, ()=>{
        state.courses=state.courses.filter(x=>x.id!==cs.id);
        toast('ลบรายวิชาเรียบร้อยแล้ว'); render();
      });
    };
    tbody.appendChild(tr);
  });
  card.appendChild(table);
  wrap.appendChild(card);
  return wrap;
}
function openCourseModal(cs){
  openModal({
    title: cs? 'แก้ไขรายวิชา' : 'เพิ่มรายวิชาใหม่',
    sub: 'ระบุรหัสวิชา ชื่อวิชา จำนวนหน่วยกิต และหลักสูตรที่สังกัด',
    fields:[
      {key:'code', label:'รหัสวิชา', placeholder:'เช่น CS101'},
      {key:'name', label:'ชื่อวิชา', placeholder:'เช่น การเขียนโปรแกรมเบื้องต้น'},
      {key:'credits', label:'จำนวนหน่วยกิต', type:'number', min:0, placeholder:'เช่น 3'},
      {key:'curriculum', label:'หลักสูตร', type:'select', options:CURRICULA}
    ],
    values: cs || {credits:3},
    submitLabel: cs? 'บันทึกการแก้ไข' : 'เพิ่มรายวิชา',
    onSubmit:(data)=>{
      data.credits = Number(data.credits)||0;
      data.code = data.code.toUpperCase();
      if(cs){ Object.assign(cs, data); toast('แก้ไขรายวิชาเรียบร้อยแล้ว'); }
      else{ state.courses.push({id:nextId++, ...data}); toast('เพิ่มรายวิชาเรียบร้อยแล้ว'); }
      render();
    }
  });
}

/* ============================================================
   1.3.2.9 UPLOAD CURRICULUM FILE
   ============================================================ */
function renderUpload(){
  const wrap=el(`<div></div>`);
  wrap.appendChild(pageHead('1.3.2.9','อัพโหลดไฟล์หลักสูตร','อัพโหลดเอกสาร มคอ. หรือไฟล์หลักสูตรฉบับสมบูรณ์เพื่อเก็บเป็นหลักฐานอ้างอิง'));
  const card=el(`<div class="card"></div>`);
  card.appendChild(el(`<div class="card-head"><h3>อัพโหลดไฟล์ใหม่</h3></div>`));
  const dz=el(`<div class="dropzone">
      <div class="glyph">⇪</div>
      <p style="font-weight:600;margin:0 0 4px;">ลากไฟล์มาวางที่นี่ หรือคลิกเพื่อเลือกไฟล์</p>
      <p style="font-size:12px;margin:0;">รองรับไฟล์ PDF, DOC, DOCX ขนาดไม่เกิน 20 MB</p>
      <input type="file" style="display:none;" id="file-input" accept=".pdf,.doc,.docx">
    </div>`);
  dz.onclick=()=>dz.querySelector('input').click();
  ['dragover','dragenter'].forEach(evt=>dz.addEventListener(evt, e=>{e.preventDefault(); dz.classList.add('drag');}));
  ['dragleave','drop'].forEach(evt=>dz.addEventListener(evt, e=>{e.preventDefault(); dz.classList.remove('drag');}));
  dz.addEventListener('drop', e=>{
    const f=e.dataTransfer.files[0];
    if(f) addFile(f);
  });
  dz.querySelector('input').addEventListener('change', e=>{
    const f=e.target.files[0];
    if(f) addFile(f);
  });
  card.appendChild(dz);

  const listWrap=el(`<div style="margin-top:20px;"></div>`);
  listWrap.appendChild(el(`<h3 style="font-size:14px;margin:0 0 4px;">ไฟล์ที่อัพโหลดแล้ว (${state.files.length})</h3>`));
  if(state.files.length===0){
    listWrap.appendChild(el(`<div class="empty-state"><div class="glyph">📄</div><p>ยังไม่มีไฟล์หลักสูตรที่อัพโหลด</p></div>`));
  } else {
    state.files.slice().reverse().forEach(f=>{
      const row=el(`<div class="file-row">
        <div class="fname">📄 ${esc(f.name)}</div>
        <div style="display:flex;align-items:center;gap:12px;">
          <span class="fmeta">${esc(f.size)} · ${esc(f.date)}</span>
          <button class="icon-btn danger" title="ลบ">🗑</button>
        </div>
      </div>`);
      row.querySelector('button').onclick=()=>{
        confirmDelete(f.name, ()=>{
          state.files=state.files.filter(x=>x.id!==f.id);
          toast('ลบไฟล์เรียบร้อยแล้ว'); render();
        });
      };
      listWrap.appendChild(row);
    });
  }
  card.appendChild(listWrap);
  wrap.appendChild(card);
  return wrap;
}
function addFile(f){
  const sizeMb=(f.size/1024/1024).toFixed(1)+' MB';
  state.files.push({id:nextId++, name:f.name, size:sizeMb, date:'วันนี้'});
  toast('อัพโหลดไฟล์หลักสูตรเรียบร้อยแล้ว');
  render();
}

/* ============================================================
   1.3.2.10 YEARS
   ============================================================ */
function renderYears(){
  const wrap=el(`<div></div>`);
  wrap.appendChild(pageHead('1.3.2.10','ปีการศึกษา','เพิ่ม ลบ แก้ไขปีการศึกษาที่ใช้อ้างอิงในระบบหลักสูตร'));
  const card=el(`<div class="card"></div>`);
  const head=el(`<div class="card-head"><h3>ปีการศึกษาทั้งหมด (${state.years.length})</h3><button class="btn btn-brass">+ เพิ่มปีการศึกษา</button></div>`);
  head.querySelector('button').onclick=()=>openSimpleModal({
    title:'เพิ่มปีการศึกษา', fields:[{key:'year', label:'ปีการศึกษา (พ.ศ.)', placeholder:'เช่น 2569'}],
    onSubmit:(d)=>{ state.years.push({id:nextId++, year:d.year}); toast('เพิ่มปีการศึกษาเรียบร้อยแล้ว'); render(); }
  });
  card.appendChild(head);
  const grid=el(`<div style="display:flex;flex-wrap:wrap;gap:12px;"></div>`);
  state.years.forEach(y=>{
    const item=el(`<div style="border:1px solid var(--border);border-radius:5px;padding:16px 20px;min-width:150px;display:flex;flex-direction:column;gap:10px;">
      <div style="font-family:'IBM Plex Mono',monospace;font-size:20px;font-weight:600;">${esc(y.year)}</div>
      <div class="row-actions" style="justify-content:flex-start;">
        <button class="icon-btn" title="แก้ไข">✎</button>
        <button class="icon-btn danger" title="ลบ">🗑</button>
      </div>
    </div>`);
    item.querySelectorAll('button')[0].onclick=()=>openSimpleModal({
      title:'แก้ไขปีการศึกษา', fields:[{key:'year', label:'ปีการศึกษา (พ.ศ.)'}], values:y,
      onSubmit:(d)=>{ y.year=d.year; toast('แก้ไขปีการศึกษาเรียบร้อยแล้ว'); render(); }
    });
    item.querySelectorAll('button')[1].onclick=()=>confirmDelete(`ปีการศึกษา ${y.year}`, ()=>{
      state.years=state.years.filter(x=>x.id!==y.id); toast('ลบปีการศึกษาเรียบร้อยแล้ว'); render();
    });
    grid.appendChild(item);
  });
  card.appendChild(grid);
  wrap.appendChild(card);
  return wrap;
}
function openSimpleModal({title, fields, values={}, onSubmit}){
  openModal({title, fields, values, submitLabel:'บันทึก', onSubmit});
}

/* ============================================================
   1.3.2.11 SEMESTERS
   ============================================================ */
function renderSemesters(){
  const wrap=el(`<div></div>`);
  wrap.appendChild(pageHead('1.3.2.11','ภาคการศึกษา','เพิ่ม ลบ แก้ไขภาคการศึกษาภายใต้แต่ละปีการศึกษา'));
  const card=el(`<div class="card"></div>`);
  const head=el(`<div class="card-head"><h3>ภาคการศึกษาทั้งหมด (${state.semesters.length})</h3><button class="btn btn-brass">+ เพิ่มภาคการศึกษา</button></div>`);
  const yearOptions = state.years.map(y=>y.year);
  head.querySelector('button').onclick=()=>openModal({
    title:'เพิ่มภาคการศึกษา',
    fields:[
      {key:'name', label:'ชื่อภาคการศึกษา', type:'select', options:['ภาคเรียนที่ 1','ภาคเรียนที่ 2','ภาคฤดูร้อน']},
      {key:'year', label:'ปีการศึกษา', type:'select', options:yearOptions}
    ],
    submitLabel:'เพิ่ม',
    onSubmit:(d)=>{ state.semesters.push({id:nextId++, ...d}); toast('เพิ่มภาคการศึกษาเรียบร้อยแล้ว'); render(); }
  });
  card.appendChild(head);
  const table=el(`<table><thead><tr><th>ภาคการศึกษา</th><th>ปีการศึกษา</th><th></th></tr></thead><tbody></tbody></table>`);
  const tbody=table.querySelector('tbody');
  state.semesters.forEach(s=>{
    const tr=el(`<tr>
      <td style="font-weight:600;">${esc(s.name)}</td>
      <td><span class="tag">${esc(s.year)}</span></td>
      <td><div class="row-actions">
        <button class="icon-btn" title="แก้ไข">✎</button>
        <button class="icon-btn danger" title="ลบ">🗑</button>
      </div></td>
    </tr>`);
    tr.querySelectorAll('button')[0].onclick=()=>openModal({
      title:'แก้ไขภาคการศึกษา',
      fields:[
        {key:'name', label:'ชื่อภาคการศึกษา', type:'select', options:['ภาคเรียนที่ 1','ภาคเรียนที่ 2','ภาคฤดูร้อน']},
        {key:'year', label:'ปีการศึกษา', type:'select', options:yearOptions}
      ],
      values:s, submitLabel:'บันทึกการแก้ไข',
      onSubmit:(d)=>{ Object.assign(s,d); toast('แก้ไขภาคการศึกษาเรียบร้อยแล้ว'); render(); }
    });
    tr.querySelectorAll('button')[1].onclick=()=>confirmDelete(`${s.name} ปีการศึกษา ${s.year}`, ()=>{
      state.semesters=state.semesters.filter(x=>x.id!==s.id); toast('ลบภาคการศึกษาเรียบร้อยแล้ว'); render();
    });
    tbody.appendChild(tr);
  });
  card.appendChild(table);
  wrap.appendChild(card);
  return wrap;
}

/* ============================================================
   1.3.2.12 STUDY PLANS
   ============================================================ */
function renderPlans(){
  const wrap=el(`<div></div>`);
  wrap.appendChild(pageHead('1.3.2.12','แผนการศึกษา','เพิ่ม ลบ แก้ไขแผนการศึกษาของแต่ละหลักสูตรในแต่ละปีการศึกษา'));
  const card=el(`<div class="card"></div>`);
  const yearOptions = state.years.map(y=>y.year);
  const head=el(`<div class="card-head"><h3>แผนการศึกษาทั้งหมด (${state.plans.length})</h3><button class="btn btn-brass">+ เพิ่มแผนการศึกษา</button></div>`);
  head.querySelector('button').onclick=()=>openModal({
    title:'เพิ่มแผนการศึกษา',
    fields:[
      {key:'name', label:'ชื่อแผนการศึกษา', placeholder:'เช่น แผนการศึกษา 4 ปี ปกติ'},
      {key:'curriculum', label:'หลักสูตร', type:'select', options:CURRICULA},
      {key:'year', label:'ปีการศึกษา', type:'select', options:yearOptions}
    ],
    submitLabel:'เพิ่ม',
    onSubmit:(d)=>{ state.plans.push({id:nextId++, ...d}); toast('เพิ่มแผนการศึกษาเรียบร้อยแล้ว'); render(); }
  });
  card.appendChild(head);
  const table=el(`<table><thead><tr><th>ชื่อแผนการศึกษา</th><th>หลักสูตร</th><th>ปีการศึกษา</th><th></th></tr></thead><tbody></tbody></table>`);
  const tbody=table.querySelector('tbody');
  state.plans.forEach(pl=>{
    const tr=el(`<tr>
      <td style="font-weight:600;">${esc(pl.name)}</td>
      <td>${esc(pl.curriculum)}</td>
      <td><span class="tag">${esc(pl.year)}</span></td>
      <td><div class="row-actions">
        <button class="icon-btn" title="แก้ไข">✎</button>
        <button class="icon-btn danger" title="ลบ">🗑</button>
      </div></td>
    </tr>`);
    tr.querySelectorAll('button')[0].onclick=()=>openModal({
      title:'แก้ไขแผนการศึกษา',
      fields:[
        {key:'name', label:'ชื่อแผนการศึกษา'},
        {key:'curriculum', label:'หลักสูตร', type:'select', options:CURRICULA},
        {key:'year', label:'ปีการศึกษา', type:'select', options:yearOptions}
      ],
      values:pl, submitLabel:'บันทึกการแก้ไข',
      onSubmit:(d)=>{ Object.assign(pl,d); toast('แก้ไขแผนการศึกษาเรียบร้อยแล้ว'); render(); }
    });
    tr.querySelectorAll('button')[1].onclick=()=>confirmDelete(pl.name, ()=>{
      state.plans=state.plans.filter(x=>x.id!==pl.id); toast('ลบแผนการศึกษาเรียบร้อยแล้ว'); render();
    });
    tbody.appendChild(tr);
  });
  card.appendChild(table);
  wrap.appendChild(card);
  return wrap;
}

/* ============================================================
   1.3.2.13 SEARCH BY DEPARTMENT
   ============================================================ */
function renderSearchDept(){
  const wrap=el(`<div></div>`);
  wrap.appendChild(pageHead('1.3.2.13','ค้นหาข้อมูลหลักสูตรตามสาขา','เลือกคณะ/สาขาวิชาเพื่อดูรายชื่อหลักสูตรทั้งหมดที่สังกัดอยู่'));
  const card=el(`<div class="card"></div>`);
  const bar=el(`<div class="search-bar">
    <select id="dept-select"><option value="">— เลือกคณะ/สาขาวิชา —</option>${state.departments.map(d=>`<option>${esc(d.name)}</option>`).join('')}</select>
  </div>`);
  const results=el(`<div id="dept-results"></div>`);
  results.appendChild(el(`<div class="empty-state"><div class="glyph">🔎</div><p>เลือกคณะ/สาขาวิชาด้านบนเพื่อแสดงรายชื่อหลักสูตร</p></div>`));
  bar.querySelector('select').onchange=(e)=>{
    results.innerHTML='';
    const dept=state.departments.find(d=>d.name===e.target.value);
    if(!dept){ results.appendChild(el(`<div class="empty-state"><div class="glyph">🔎</div><p>เลือกคณะ/สาขาวิชาด้านบนเพื่อแสดงรายชื่อหลักสูตร</p></div>`)); return; }
    dept.curricula.forEach(curr=>{
      const courseCount=state.courses.filter(c=>c.curriculum===curr).length;
      const instCount=state.respInstructors.filter(i=>i.curriculum===curr).length;
      results.appendChild(el(`<div class="result-card">
        <h4>${esc(curr)}</h4>
        <div class="meta">สังกัด ${esc(dept.name)}</div>
        <div class="chip-row">
          <span class="tag brass">${courseCount} รายวิชา</span>
          <span class="tag sage">${instCount} อาจารย์ผู้รับผิดชอบ</span>
        </div>
      </div>`));
    });
  };
  card.appendChild(bar);
  card.appendChild(results);
  wrap.appendChild(card);
  return wrap;
}

/* ============================================================
   1.3.2.14 SEARCH BY CURRICULUM
   ============================================================ */
function renderSearchCurr(){
  const wrap=el(`<div></div>`);
  wrap.appendChild(pageHead('1.3.2.14','ค้นหาข้อมูลหลักสูตรตามหลักสูตร','เลือกหลักสูตรเพื่อดูรายละเอียดรายวิชาและอาจารย์ผู้รับผิดชอบ'));
  const card=el(`<div class="card"></div>`);
  const bar=el(`<div class="search-bar">
    <select id="curr-select"><option value="">— เลือกหลักสูตร —</option>${CURRICULA.map(c=>`<option>${esc(c)}</option>`).join('')}</select>
  </div>`);
  const results=el(`<div id="curr-results"></div>`);
  results.appendChild(el(`<div class="empty-state"><div class="glyph">🔎</div><p>เลือกหลักสูตรด้านบนเพื่อแสดงรายละเอียด</p></div>`));
  bar.querySelector('select').onchange=(e)=>{
    results.innerHTML='';
    const curr=e.target.value;
    if(!curr){ results.appendChild(el(`<div class="empty-state"><div class="glyph">🔎</div><p>เลือกหลักสูตรด้านบนเพื่อแสดงรายละเอียด</p></div>`)); return; }
    const courses=state.courses.filter(c=>c.curriculum===curr);
    const insts=state.respInstructors.filter(i=>i.curriculum===curr);
    const totalCredits=courses.reduce((s,c)=>s+c.credits,0);
    const card1=el(`<div class="result-card">
      <h4>${esc(curr)}</h4>
      <div class="meta">${courses.length} รายวิชา · ${totalCredits} หน่วยกิตรวม · ${insts.length} อาจารย์ผู้รับผิดชอบ</div>
      <div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;">
        ${courses.map(c=>`<span class="stamp">${esc(c.code)}</span>`).join('')}
      </div>
      <div style="margin-top:12px;font-size:12.5px;color:var(--slate);font-weight:600;">อาจารย์ผู้รับผิดชอบหลักสูตร</div>
      <div class="chip-row" style="margin-top:6px;">
        ${insts.map(i=>`<span class="tag">${esc(i.name)}</span>`).join('') || '<span style="font-size:12.5px;color:var(--slate);">ยังไม่มีข้อมูล</span>'}
      </div>
    </div>`);
    results.appendChild(card1);
  };
  card.appendChild(bar);
  card.appendChild(results);
  wrap.appendChild(card);
  return wrap;
}

/* ============================================================
   1.3.2.15 CONTACT MESSAGES (view only)
   ============================================================ */
function renderMessages(){
  const wrap=el(`<div></div>`);
  const unread=state.messages.filter(m=>!m.read).length;
  wrap.appendChild(pageHead('1.3.2.15','ข้อความติดต่อสอบถาม', `แสดงผลข้อความติดต่อสอบถามที่ส่งเข้ามาจากผู้ใช้ทั่วไป${unread? ` · ยังไม่ได้อ่าน ${unread} รายการ`:''}`));
  const card=el(`<div class="card"></div>`);
  if(state.messages.length===0){
    card.appendChild(el(`<div class="empty-state"><div class="glyph">✉️</div><p>ยังไม่มีข้อความติดต่อสอบถามเข้ามา</p></div>`));
  } else {
    state.messages.slice().reverse().forEach(m=>{
      const item=el(`<div class="msg-item ${m.read?'':'unread'}">
        <div class="msg-top">
          <h4>${esc(m.name)}</h4>
          <span class="msg-date">${esc(m.date)}${m.read? '' : ' · ยังไม่ได้อ่าน'}</span>
        </div>
        <div class="msg-subject">${esc(m.subject)}</div>
        <p>${esc(m.body)}</p>
        <div style="margin-top:10px;display:flex;justify-content:space-between;align-items:center;">
          <span style="font-size:12px;color:var(--slate);">${esc(m.email)}</span>
          ${m.read? '' : '<button class="btn btn-outline" style="padding:6px 12px;font-size:12px;">ทำเครื่องหมายว่าอ่านแล้ว</button>'}
        </div>
      </div>`);
      const btn=item.querySelector('button');
      if(btn) btn.onclick=()=>{ m.read=true; render(); };
      card.appendChild(item);
    });
  }
  wrap.appendChild(card);
  return wrap;
}

/* ============================================================
   AUTH
   ============================================================ */
document.getElementById('login-form').addEventListener('submit', e=>{
  e.preventDefault();
  const user=document.getElementById('login-user').value.trim();
  const pass=document.getElementById('login-pass').value.trim();
  if(!user || !pass){
    document.getElementById('login-error').style.display='block';
    return;
  }
  document.getElementById('login-error').style.display='none';
  document.getElementById('login-screen').style.display='none';
  document.getElementById('app').style.display='block';
  document.getElementById('user-name').textContent=state.profile.name;
  document.getElementById('user-avatar').textContent=initials(state.profile.name);
  navigate('profile');
});
document.getElementById('logout-btn').addEventListener('click', ()=>{
  document.getElementById('app').style.display='none';
  document.getElementById('login-screen').style.display='flex';
  document.getElementById('login-user').value='';
  document.getElementById('login-pass').value='';
});
document.getElementById('menu-toggle').addEventListener('click', ()=>{
  document.getElementById('sidebar').classList.toggle('open');
});
</script>
</body>
</html>
