/*****************************************************************************
 * 
 * Author : Laurent REBIERE
 * Enterprise : Expert-solutions
 * 
 *  
 * 
 * ***************************************************************************/


document.SAMPLER_VERBOSE = 2;

function trace(msg,verbose){
    // console.log(msg);
    if (verbose !== null && verbose <= document.SAMPLER_VERBOSE) console.log(msg); // typeof verbose == "number" && 
}


/******************************************************************
 * 
 * Mathematical utils
 * 
 * ***************************************************************/

function prodVect3(u,v) {
    return [u[1] * v[2] - u[2] * v[1],
            u[2] * v[0] - u[0] * v[2],
            u[0] * v[1] - u[1] * v[0]]
}

function getIdentityMatrix(dim) {
    var res = [];
    for (var c = 0;c<dim;c++) {
        var cm = [];
        for (var l =0;l<dim;l++) {
            if (c == l) cm.push(1); else cm.push(0);
        }
        res.push(cm);
    }
    return res;
}

function getRotMatrix(M) {
    const nM = M.slice();
    for(var l=0;l<3;l++) {nM[l] = nM[l].slice(); nM[l][3] = 0;}
    return nM;
}

function prodMatrice(M1,M2){
return [  [ M1[0][0] * M2[0][0] + M1[0][1] * M2[1][0] + M1[0][2] * M2[2][0] + M1[0][3] * M2[3][0], 
            M1[0][0] * M2[0][1] + M1[0][1] * M2[1][1] + M1[0][2] * M2[2][1] + M1[0][3] * M2[3][1],
            M1[0][0] * M2[0][2] + M1[0][1] * M2[1][2] + M1[0][2] * M2[2][2] + M1[0][3] * M2[3][2],
            M1[0][0] * M2[0][3] + M1[0][1] * M2[1][3] + M1[0][2] * M2[2][3] + M1[0][3] * M2[3][3]
          ],
          [ M1[1][0] * M2[0][0] + M1[1][1] * M2[1][0] + M1[1][2] * M2[2][0] + M1[1][3] * M2[3][0], 
            M1[1][0] * M2[0][1] + M1[1][1] * M2[1][1] + M1[1][2] * M2[2][1] + M1[1][3] * M2[3][1],
            M1[1][0] * M2[0][2] + M1[1][1] * M2[1][2] + M1[1][2] * M2[2][2] + M1[1][3] * M2[3][2],
            M1[1][0] * M2[0][3] + M1[1][1] * M2[1][3] + M1[1][2] * M2[2][3] + M1[1][3] * M2[3][3]
          ],
          [ M1[2][0] * M2[0][0] + M1[2][1] * M2[1][0] + M1[2][2] * M2[2][0] + M1[2][3] * M2[3][0], 
            M1[2][0] * M2[0][1] + M1[2][1] * M2[1][1] + M1[2][2] * M2[2][1] + M1[2][3] * M2[3][1],
            M1[2][0] * M2[0][2] + M1[2][1] * M2[1][2] + M1[2][2] * M2[2][2] + M1[2][3] * M2[3][2],
            M1[2][0] * M2[0][3] + M1[2][1] * M2[1][3] + M1[2][2] * M2[2][3] + M1[2][3] * M2[3][3]
          ],
          [ M1[3][0] * M2[0][0] + M1[3][1] * M2[1][0] + M1[3][2] * M2[2][0] + M1[3][3] * M2[3][0], 
            M1[3][0] * M2[0][1] + M1[3][1] * M2[1][1] + M1[3][2] * M2[2][1] + M1[3][3] * M2[3][1],
            M1[3][0] * M2[0][2] + M1[3][1] * M2[1][2] + M1[3][2] * M2[2][2] + M1[3][3] * M2[3][2],
            M1[3][0] * M2[0][3] + M1[3][1] * M2[1][3] + M1[3][2] * M2[2][3] + M1[3][3] * M2[3][3]
          ] ];  }

function prodMatriceX3(M1,M2,M3){
 //   trace("prodMatriceX3");
 //   trace(M1);
 //   trace(M2);
 //   trace(M3);
    return prodMatrice(M1,prodMatrice(M2,M3));}

function prodMatriceArray(matArray) {
    if (Array.isArray(matArray) && matArray.length == 1)
        return matArray[0];
    else if (Array.isArray(matArray) && matArray.length == 2) 
        return prodMatrice(matArray[0],matArray[1]);
    else if (Array.isArray(matArray) && matArray.length > 2) {
        var carr = matArray.slice();
        return prodMatrice(carr.shift(),prodMatriceArray(carr));
    } else return getIdentityMatrix(4);
}

function getX_prodMatriceVecteur(matrix,x,y,z){
  return x * matrix[0][0] + y * matrix[0][1] + z * matrix[0][2] + matrix[0][3];
}
 
function getY_prodMatriceVecteur(matrix,x,y,z){
  return x * matrix[1][0] + y * matrix[1][1] + z * matrix[1][2] + matrix[1][3];
}

function getZ_prodMatriceVecteur(matrix,x,y,z){
  return x * matrix[2][0] + y * matrix[2][1] + z * matrix[2][2] + matrix[2][3];
}

function getRotXMatrix(T) {
    return [   
        [1, 0,            0,                 0],
        [0, Math.cos(T), -(Math.sin(T)),     0],
        [0, Math.sin(T), (Math.cos(T)),      0],
        [0, 0,            0,                 1]   
    ];
}

function getRotYMatrix(T) {
    return [   
        [Math.cos(T),    0, (Math.sin(T)),     0],
        [0,              1, 0,                 0],
        [-(Math.sin(T)), 0, (Math.cos(T)),     0],
        [0,              0, 0,                 1]    
    ];
}

function getRotZMatrix(T) {
    return [   
        [Math.cos(T), -(Math.sin(T)), 0,     0],
        [Math.sin(T), (Math.cos(T)),  0,     0],
        [0,             0,            1,     0],
        [0,             0,            0,     1]    
    ];
}

function getTransMatrix(x,y,z) {
    return [ 
        [1,0,0,x],
        [0,1,0,y],
        [0,0,1,z],
        [0,0,0,1]
    ];
}
// t % (0 .. 1)
function getBezierMatrix(t, pt3, pt1, pt2) {
    const tmp1 = 3 * t * Math.pow(1 - t, 2); 
    const tmp2 = 3 * Math.pow(t,2) * (1 - t);
    const tmp3 = Math.pow(t,3);
    const x = tmp1 * pt1[0] + tmp2 * pt2[0] + tmp3 * pt3[0];
    const y = tmp1 * pt1[1] + tmp2 * pt2[1] + tmp3 * pt3[1];
    const z = tmp1 * pt1[2] + tmp2 * pt2[2] + tmp3 * pt3[2];
    return getTransMatrix(x,y,z);
}


/******************************************************************************
 * 
 * Events controller
 * 
 * ****************************************************************************/

class Events {
    constructor(canvas_obj) {
        this.fullscreen = false;
        this.canvas = canvas_obj;
        this.context = this.canvas.getContext("2d");
        this.drawStage = undefined;
        this.listening = false;
        
        // desktop flags
        this.mousePos = null;
        this.mouseDown = false;
        this.mouseUp = false;
        this.mouseOver = false;
        this.mouseMove = false;
        
        // mobile flags
        this.touchPos = null;
        this.touchStart = false;
        this.touchMove = false;
        this.touchEnd = false;
        
        // Region Events
        this.currentRegion = null;
        this.regionIndex = 0;
        this.lastRegionIndex = -1;
        this.mouseOverRegionIndex = -1;
    }

    getContext() {
        return this.context;
    }

    getCanvas() {
        return this.canvas;
    }

    clear() {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

    getCanvasPos() {
        let obj = this.getCanvas();
        let top = 0;
        let left = 0;
        while (obj != null && obj.tagName != "BODY") {
            top += obj.offsetTop;
            left += obj.offsetLeft;
            obj = obj.offsetParent;
       //     trace(obj,0);
        }
        return {
            top: top,
            left: left
        };
    }

    setDrawStage(func) {
        this.drawStage = func;
        this.listen();
    }

    reset(evt) {
        if (!evt) {
            evt = window.event;
        }
        
        this.setMousePosition(evt);
        this.setTouchPosition(evt);
        this.regionIndex = 0;
        
        if (!this.animating && this.drawStage !== undefined) {
            this.drawStage();
        }
        
        // desktop flags
        this.mouseOver = false;
        this.mouseMove = false;
        this.mouseDown = false;
        this.mouseUp = false;
        
        // mobile touch flags
        this.touchStart = false;
        this.touchMove = false;
        this.touchEnd = false;
    }

    listen() {
        const that = this;
        
        if (this.drawStage !== undefined) {
            this.drawStage();
        }
        
        // desktop events
        this.canvas.addEventListener("mousedown", evt => {
            that.mouseDown = true;
            that.reset(evt);
        }, false);
        
        this.canvas.addEventListener("mousemove", evt => {
            that.reset(evt);
        }, false);

        this.canvas.addEventListener("wheel", evt => {
            that.reset(evt);
        }, false);
        
        this.canvas.addEventListener("mouseup", evt => {
            that.mouseUp = true;
            that.reset(evt);
        }, false);
        
        this.canvas.addEventListener("mouseover", evt => {
            that.reset(evt);
        }, false);
        
        this.canvas.addEventListener("mouseout", evt => {
            that.mousePos = null;
            that.reset(evt);
        }, false);
        
        // mobile events
        this.canvas.addEventListener("touchstart", evt => {
            evt.preventDefault();
            that.touchStart = true;
            that.reset(evt);
        }, false);
        
        this.canvas.addEventListener("touchmove", evt => {
            evt.preventDefault();
            that.reset(evt);
        }, false);
        
        this.canvas.addEventListener("touchend", evt => {
            evt.preventDefault();
            that.touchEnd = true;
            that.reset(evt);
        }, false);
    }

    getMousePos(evt) {
        return this.mousePos;
    }

    getTouchPos(evt) {
        return this.touchPos;
    }

    setMousePosition(evt) {
        var xoff = 0;
        var yoff = 0;
        if (this.fullscreen == false) xoff = window.pageXOffset;
        if (this.fullscreen == false) yoff = window.pageYOffset;
        const canvPos = this.getCanvasPos();
        const mouseX = evt.clientX - canvPos.left + xoff;
        const mouseY = evt.clientY - canvPos.top + yoff;
        this.mousePos = {
            x: mouseX,
            y: mouseY
        };
    }

    setTouchPosition(evt) {
        if (evt.touches !== undefined && evt.touches.length < 3) { // Only deal with one finger
            const touch = evt.touches[0]; // Get the information for finger #1
            const touch2 = evt
            const canvPos = this.getCanvasPos();
            var touchX = touch.pageX - canvPos.left;
            var touchY = touch.pageY - canvPos.top;
            var touchX2 = touchX;
            var touchY2 = touchY;
            if (evt.touches.length == 2) {
                const touch2 = evt.touches[1];
                touchX2 = touch2.pageX - canvPos.left;
                touchY2 = touch2.pageY - canvPos.top;
            }
            if (this.fullscreen) {
                touchX -= window.pageXOffset;
                touchY -= window.pageYOffset;
                if (evt.touches.length == 2) {
                touchX2 -= window.pageXOffset;
                touchY2 -= window.pageYOffset;
                }                
            }

            this.touchPos = {
                x: touchX,
                y: touchY,
                x2:touchX2,
                y2:touchY2
            };
        }
    }

    beginRegion() {
        this.currentRegion = {};
        this.regionIndex++;
    }

    addRegionEventListener(type, func) {
        let event = (type.indexOf('touch') == -1) ? `on${type}` : type;
        this.currentRegion[event] = func;
    }

    closeRegion() {
        const pos = this.touchPos || this.mousePos;
        
        if (pos !== null && this.context.isPointInPath(pos.x, pos.y)) {
            if (this.lastRegionIndex != this.regionIndex) {
                this.lastRegionIndex = this.regionIndex;
            }
            
            // handle onmousedown
            if (this.mouseDown && this.currentRegion.onmousedown !== undefined) {
                this.currentRegion.onmousedown();
                this.mouseDown = false;
            }
            
            // handle onmouseup
            else if (this.mouseUp && this.currentRegion.onmouseup !== undefined) {
                this.currentRegion.onmouseup();
                this.mouseUp = false;
            }
            
            // handle onmouseover
            else if (!this.mouseOver && this.regionIndex != this.mouseOverRegionIndex && this.currentRegion.onmouseover !== undefined) {
                this.currentRegion.onmouseover();
                this.mouseOver = true;
                this.mouseOverRegionIndex = this.regionIndex;
            }
            
            // handle onmousemove
            else if (!this.mouseMove && this.currentRegion.onmousemove !== undefined) {
                this.currentRegion.onmousemove();
                this.mouseMove = true;
            }
            
            // handle touchstart
            if (this.touchStart && this.currentRegion.touchstart !== undefined) {
                this.currentRegion.touchstart();
                this.touchStart = false;
            }
            
            // handle touchend
            if (this.touchEnd && this.currentRegion.touchend !== undefined) {
                this.currentRegion.touchend();
                this.touchEnd = false;
            }
            
            // handle touchmove
            if (!this.touchMove && this.currentRegion.touchmove !== undefined) {
                this.currentRegion.touchmove();
                this.touchMove = true;
            }
            
        }
        else if (this.regionIndex == this.lastRegionIndex) {
            this.lastRegionIndex = -1;
            this.mouseOverRegionIndex = -1;
            
            // handle mouseout condition
            if (this.currentRegion.onmouseout !== undefined) {
                this.currentRegion.onmouseout();
            }
        }
    }
}



/******************************************************************************
 * 
 * Buffers Calculating
 * 
 * ***************************************************************************/

//
function getBuffersForStructGroup(gl,local_obj,sequences,indexSeq,timeInSeq) {
    trace("getBuffersForStructGroup(" + local_obj.tag + "," + indexSeq + ", " + timeInSeq + ")");
    trace(local_obj);

    if (Array.isArray(local_obj.things)) {
 
    // Constante du coeff de fermeture (0 .. 100)
        const sequence_duration = sequences[indexSeq].duration;
        const pause_duration = sequences[indexSeq].pause;
        const seqPlusPause = sequence_duration + pause_duration;
        const kt = Math.min(1.0,(timeInSeq  / sequence_duration ));
        const coeffF_def = 100.0 * kt;
 
        const _motions = sequences[indexSeq].motions;
        const _actions = sequences[indexSeq].action_calls;

        const mot2remove = [];
        const act2remove = [];

        //rot:[~I], move:[~I], bpt1:[~I], bpt2:[~I]
        // 
        const _tag = local_obj.tag;
        trace("actions to perform :");
        trace(_actions);
        for(j=0;j<_actions.length;j++) {
            const act = _actions[j];
            
            if (act.targets.includes(_tag) && Array.isArray(local_obj.actions)) {
                local_obj.actions.forEach(function(act2){
                    if (act2.name == act.action && Array.isArray(act2.motions) && act2.motions.length > 0) {
                        trace("fill action call : " + act2.name + ", got motions !");
                        act2.motions.forEach(function(_mot){
                            _mot.Msetups = [];
                            if (Array.isArray(act.Msetups)) _mot.Msetups = act.Msetups.slice();
                            if (Array.isArray(local_obj.Msetup)) _mot.Msetups.push(local_obj.Msetup);
                            _motions.push(_mot);
                            mot2remove.push(_mot);
                          });
                    }
                    if (act2.name == act.action && Array.isArray(act2.action_calls) && act2.action_calls.length > 0) {
                        trace("fill action call : " + act2.name + ", got action_calls !");
                        act2.action_calls.forEach(function(_act){
                            _act.Msetups = [];
                            if (Array.isArray(act.Msetups)) _act.Msetups = act.Msetups.slice();
                            if (Array.isArray(local_obj.Msetup)) _act.Msetups.push(local_obj.Msetup);
                            _actions.push(_act);
                            act2remove.push(_act);
                        });
                    }
                });
            }
        }
        trace("motions MAJ : ");
        trace(_motions);
        trace(sequences[indexSeq].motions);
        trace("actions MAJ : ");
        trace(_actions);
        trace(sequences[indexSeq].actions);


        var positions = [];
        var normals = [];
        var colors = [];
        var indices = [];
        var textureCoordinates = [];
        var countVertices = 0;

        const struct_obj_array = local_obj.things;
        const nbObj = struct_obj_array.length;
        trace("nombre d'objet=" + nbObj);
        var curInd = 0;

        var ranges = [];

        var rotations = [0,0,0];
        var translations = [0,0,0];
        var bezier_translations = [[],[],[]];

        const tag = local_obj.tag;
        for(j=0;j<_motions.length;j++) {
            const mot = _motions[j];
            if (mot.targets.includes(tag)) {
                trace("apply motion :");
                trace(mot);

                if (Array.isArray(mot.rotate) && mot.rotate.length === 3) {
                    rotations[0] += mot.rotate[0] * kt * Math.PI / 180;
                    rotations[1] += mot.rotate[1] * kt * Math.PI / 180;
                    rotations[2] += mot.rotate[2] * kt * Math.PI / 180;
                }
                if (Array.isArray(mot.bezier_pt2) && Array.isArray(mot.bezier_pt1) && Array.isArray(mot.moveTo) && 
                        mot.bezier_pt1.length === 3 && mot.bezier_pt2.length === 3 && mot.moveTo.length === 3) {
                    bezier_translations[0] = [mot.moveTo[0] * kt, mot.moveTo[1] * kt, mot.moveTo[2] * kt];
                    bezier_translations[1] = [mot.bezier_pt1[0] * kt, mot.bezier_pt1[1] * kt, mot.bezier_pt1[2] * kt];
                    bezier_translations[2] = [mot.bezier_pt2[0] * kt, mot.bezier_pt2[1] * kt, mot.bezier_pt2[2] * kt];
                } else if (Array.isArray(mot.moveTo) && mot.moveTo.length === 3) {
                    var ikt = kt;
                   // if (rotations[0] + rotations[1] + rotations[2] > 0) ikt = 1;
                    translations[0] += mot.moveTo[0] * ikt;
                    translations[1] += mot.moveTo[1] * ikt;
                    translations[2] += mot.moveTo[2] * ikt;
                }
            }
        }
    // Construction des matrices

        const MatRot = prodMatriceX3(getRotZMatrix(rotations[2]),getRotYMatrix(rotations[1]),getRotXMatrix(rotations[0]));
     //   trace("MatRot");
     //   trace(MatRot);

        const MatTrans = getTransMatrix(translations[0],translations[1],translations[2]);
     //   trace("MatTrans");
     //   trace(MatTrans);

        var MatMotion = prodMatrice(MatTrans,MatRot);
//        var MatMotion = prodMatrice(MatRot,MatTrans);

        if (bezier_translations[0].length === 3) {
            const MatBezier = getBezierMatrix(coeffF_def / 100,bezier_translations[0],bezier_translations[1],bezier_translations[2]);
            MatMotion = prodMatrice(MatBezier,MatMotion);
        } 



        var _Msetups = [];
/*
        var Msetup_tmp = local_obj.Msetup;
        if (Array.isArray(local_obj.Msetups)) Msetup_tmp = prodMatrice(prodMatriceArray(_Msetups),Msetup_tmp);
*/
//        const _Msetup = prodMatrice(MatMotion,Msetup_tmp);
//        const _Msetup = prodMatrice(Msetup_tmp,MatMotion);
        const _Msetup = prodMatriceArray([prodMatriceArray(local_obj.Msetups),MatMotion,local_obj.Msetup]);

        if (timeInSeq >= seqPlusPause && local_obj.setup === 0) {
            local_obj.setup = 1;
//            const _Msetup0 = prodMatrice(MatMotion,local_obj.Msetup);
            const _Msetup0 = prodMatrice(MatMotion,local_obj.Msetup);
            trace(" fin sequence -> MAJ Msetup");
            trace(local_obj.Msetup);
            trace(_Msetup0);
          //  if (tag == "S3>L3>G2") alert("continue ?");
            local_obj.Msetup = _Msetup0;
            local_obj.Msetups = []; // !!!!!!!!!!!!!!!!
        }

//        _Msetups.push(local_obj.Msetup);
        _Msetups.push(_Msetup);

        trace("Msetups MAJ : ");
        trace(_Msetups);
        var sizeOfPos = 0;
        var VC = 0;
      // MAJ des buffers
        while(curInd<nbObj) {
            const _obj = struct_obj_array[curInd];
            _obj.Msetups = _Msetups.slice();
            trace("curInd:" + curInd);
            // trace(local_obj);
            VC += getBuffersForStructGroup(gl,_obj,sequences,indexSeq,timeInSeq);
            _obj.Msetups = [];
            // trace("curInd:" + curInd);
            curInd++;
            // trace("curInd:" + curInd);
        }
        act2remove.forEach(function(_act){_actions.pop()});
        mot2remove.forEach(function(_mot){_motions.pop()});

      /*  local_obj.range = ranges;
        trace(local_obj.tag + ".range :");
        trace(local_obj.range); */
        local_obj.vertexCount = VC;
        return local_obj.vertexCount;
    } else {
        return getBuffersForStruct(gl,local_obj,sequences,indexSeq,timeInSeq);
    }
}

//
function getBuffersForStruct(gl,struct_obj,sequences, indexSeq,timeInSeq) {
    trace("getBuffersForStruct(" + indexSeq + ", " + timeInSeq + ")");
    trace(struct_obj);

// Buffer des indices
    const indices = struct_obj.indices;

// Buffer des couleurs  
    const colors = struct_obj.colors;

// Buffer des coordonnees de texture  
    const textureCoordinates = struct_obj.textures;
    const frontXoffset = struct_obj.frontAtlasXYoffset[0];
    const frontYoffset = struct_obj.frontAtlasXYoffset[1];
    const backXoffset = struct_obj.backAtlasXYoffset[0];
    const backYoffset = struct_obj.backAtlasXYoffset[1];
    const sideXoffset = struct_obj.sideAtlasXYoffset[0];
    const sideYoffset = struct_obj.sideAtlasXYoffset[1];

// Constante du coeff de fermeture (0 .. 100)
    const sequence_duration = sequences[indexSeq].duration;
    const pause_duration = sequences[indexSeq].pause;
    const seqPlusPause = sequence_duration + pause_duration;

//    const kt = Math.min(1.0,(Math.max(0.0,(timeInSeq - pause_duration)) / sequence_duration ));
    const kt = Math.min(1.0,(timeInSeq  / sequence_duration ));
    const coeffF_def = 100.0 * kt;

  /*  trace("sequence_duration=" + sequence_duration);
    trace("pause_duration=" + pause_duration);
    trace("seqPlusPause=" + seqPlusPause);

    trace("kt=" + kt);
*/

// Selection des transformations concernant l objet courant
    const _motions = sequences[indexSeq].motions;

    var group2open = []; 
    var group2close = [];
    var rotations = [0,0,0];
    var translations = [0,0,0];
    var bezier_translations = [[],[],[]];

    //rot:[~I], move:[~I], bpt1:[~I], bpt2:[~I]
    const tag = struct_obj.tag;
    for(j=0;j<_motions.length;j++) {
        const mot = _motions[j];
        if (mot.targets.includes(tag)) {
      //      trace("apply motion :");
      //      trace(mot);

            if (Array.isArray(mot.groupsToOpen)) {
                for (i=0;i<mot.groupsToOpen.length;i++) {
                    group2open.push(mot.groupsToOpen[i]);
                }}
            if (Array.isArray(mot.groupsToClose)) {
                for (i=0;i<mot.groupsToClose.length;i++) {
                    group2close.push(mot.groupsToClose[i]);
                }}
            if (Array.isArray(mot.rotate) && mot.rotate.length === 3) {
                rotations[0] += mot.rotate[0] * kt * Math.PI / 180;
                rotations[1] += mot.rotate[1] * kt * Math.PI / 180;
                rotations[2] += mot.rotate[2] * kt * Math.PI / 180;
            }
            if (Array.isArray(mot.bezier_pt2) && Array.isArray(mot.bezier_pt1) && Array.isArray(mot.moveTo) && 
                    mot.bezier_pt1.length === 3 && mot.bezier_pt2.length === 3 && mot.moveTo.length === 3) {
                bezier_translations[0] = [mot.moveTo[0] * kt, mot.moveTo[1] * kt, mot.moveTo[2] * kt];
                bezier_translations[1] = [mot.bezier_pt1[0] * kt, mot.bezier_pt1[1] * kt, mot.bezier_pt1[2] * kt];
                bezier_translations[2] = [mot.bezier_pt2[0] * kt, mot.bezier_pt2[1] * kt, mot.bezier_pt2[2] * kt];
            } else if (Array.isArray(mot.moveTo) && mot.moveTo.length === 3) {
                translations[0] += mot.moveTo[0] * kt;
                translations[1] += mot.moveTo[1] * kt;
                translations[2] += mot.moveTo[2] * kt;
            }
        }
    }
   // trace(group2open);
  //  trace(group2close);

// Construction des matrices de mouvement

    const MatRot = prodMatriceX3(getRotZMatrix(rotations[2]),getRotYMatrix(rotations[1]),getRotXMatrix(rotations[0]));
 //   trace("MatRot");
 //   trace(MatRot);

    const MatTrans = getTransMatrix(translations[0],translations[1],translations[2]);
 //   trace("MatTrans");
 //   trace(MatTrans);

//    var MatMotion = prodMatrice(MatTrans,MatRot);
    var MatMotion = prodMatrice(MatRot,MatTrans);

    if (bezier_translations[0].length === 3) {
        const MatBezier = getBezierMatrix(coeffF_def / 100,bezier_translations[0],bezier_translations[1],bezier_translations[2]);
        MatMotion = prodMatrice(MatBezier,MatMotion);
    } 

    const _Msetup = prodMatriceArray([prodMatriceArray(struct_obj.Msetups),MatMotion,struct_obj.Msetup]);

    if (timeInSeq >= seqPlusPause && struct_obj.setup === 0) {
        trace("MAJ Msetup !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
        struct_obj.setup = 1;
        const _Msetup0 = prodMatrice(MatMotion,struct_obj.Msetup);
        struct_obj.Msetup = _Msetup0;
    }


 //   trace("Msetup");
 //   trace(Msetup);

    // Matrice de transformation par face
    // var theta = [];
    const _e = struct_obj.extrusion;

    var LMatResult = [];
    // nombre de point pour chaque pli
    const nbFragmentsPli = struct_obj.nbFoldFragment; // 
    const nbFaces = struct_obj.theta_cur_angle.length;

    for(var i=0;i<nbFaces;i++) {
        var MatResult = [];
        const motherIndex = struct_obj.mother[i];
        const close_angle = struct_obj.theta_close_angle[i];
        const close_angle_rad = close_angle * Math.PI / 180;
        const radiusC = struct_obj.radius_curv[i];
        const cur_angle = struct_obj.theta_cur_angle[i];
        const grp = struct_obj.theta_grp[i];
        
        if (i > 0 && group2open.includes(grp)) {
            var new_angle = close_angle_rad * (1 - kt); 
            if (close_angle < 0) {new_angle = Math.min(0,new_angle);}
            else {new_angle = Math.max(0,new_angle);}
            struct_obj.theta_cur_angle[i] = new_angle;
        }
        if (i > 0 && group2close.includes(grp)) {
            var new_angle = 0 + close_angle_rad * kt; 
            if (close_angle < 0) {new_angle = Math.max(close_angle_rad,new_angle);}
            else {new_angle = Math.min(close_angle_rad,new_angle);}
            struct_obj.theta_cur_angle[i] = new_angle;
        }       
        const theta = struct_obj.theta_cur_angle[i];
        
     //   trace("face:" + i + ", grp:" + grp + ", close angle:" + close_angle + ", cur_angle:" + cur_angle + ", new_angle:" + theta);
        
        for (var ifrag=0;ifrag<=nbFragmentsPli;ifrag++) {
            if (i > 0) {
                const thetal = (theta * ifrag / nbFragmentsPli);

                const Rt = [[1.0, 0.0,                  0.0,              0.0],
                            [0.0, Math.cos(thetal),      Math.sin(thetal),  0.0],
                            [0.0, -(Math.sin(thetal)),   Math.cos(thetal),  0.0],
                            [0.0, 0.0,                  0.0,              1.0]];
                
                // translation au pliage
                const Ty = ((_e+radiusC)*(Math.abs(thetal)) ); // * _e / (_e + radiusC)); // /Math.PI);
                const Trth = [[1,0,0,0],[0,1,0,Ty],[0,0,1,0],[0,0,0,1]];

                // décalage du centre de rotation
                // si close_angle est négatif
                var Dz = _e - radiusC;
                // décalage sur y du au décalage initial lors de l'insertion des fragments de pli
                var Dy = -(Math.abs((_e+radiusC)*close_angle_rad)); // - Math.abs(thetal)) ));
                // si close_angle est positif
                if (close_angle >= 0) Dz = -(radiusC);
                const TrDz = [[1,0,0,0],[0,1,0,Dy],[0,0,1,Dz],[0,0,0,1]];
                const invTrDz = [[1,0,0,0],[0,1,0,-(Dy)],[0,0,1,-(Dz)],[0,0,0,1]];

                if (struct_obj.invRa[i].length === 4) {
                    const invra = struct_obj.invRa[i];
                    const TRa = struct_obj.TRa[i];
                    const MatRes = prodMatriceArray([LMatResult[motherIndex][nbFragmentsPli], TRa, invTrDz, Rt, TrDz, Trth, invra]);
                    MatResult.push(MatRes);
                } else {
                    const TRa = struct_obj.TRa[i];
                    const MatRes = prodMatriceArray([LMatResult[motherIndex][nbFragmentsPli], TRa, invTrDz, Rt, TrDz, Trth]);
                    MatResult.push(MatRes);
                }
            } else MatResult.push(_Msetup);
        }
        LMatResult.push(MatResult);
    }
 
   function initMatIndexes(_obj,nbFragmentsPli) {
        const matOffset = gl.bufferContener.glMatP.length;
    // MAJ des positions

        const nbF = _obj.range.length;
        var iF = 0;
        var countMat = 0;
        var tmplIndMat=[];
        // iteration sur les faces
        while(iF<nbF) {    
            const R = _obj.range[iF];
            // index point surfacique
            var iPt = R[0];
            // iteration sur le range des index de points de la face
            while(iPt<R[1]){
                // index de la matrice resultante des transformations des positions et des normales
                const indexFrag = Math.min(nbFragmentsPli, parseInt((iPt-R[0]) / 2));
                const indexMat = matOffset+(iF*(nbFragmentsPli+1))+indexFrag;
                tmplIndMat.push(indexMat);
                iPt++;
            }
            iF++; 
        }
        gl.bufferContener.gPtIMat = gl.bufferContener.gPtIMat.concat(tmplIndMat);
        trace("initMatIndexes "+_obj.tag + ", nbFrag:" + nbFragmentsPli + ", nbF:" + nbF + ", matOffset:"+matOffset,0);
        trace(tmplIndMat,0);
    }
 //   trace("LMatResult");
  //  trace(LMatResult);
    function initStructBuffers(_obj) {
    // extrusion des faces
    /*
    pour chaque point de Ro -> 6 points 
    p1 : point origine po
    p2 : point extrudé pe
    p3 : po connex 1 poc1
    p4 : po connex 2 poc2
    P5 : pe connex 1 pec1
    p6 : pe connex 2 pec2
    */

     //  const _e = _obj.extrusion;
    // Buffer des positions
        const positions = _obj.positions;

    // Buffer des normals
        var normals = [];
        normals.length = positions.length;
        for (var ipt = 0; ipt<normals.length/3; ipt++) {
            const ic = ipt*3;
            normals[ic]=0;
            normals[ic+1]=0;
            normals[ic+2]=1;
        }
        const nbF = _obj.range.length;
 //       const Po = positions;
        var P = []; // ok
        var I = []; // ok
        var N = []; // ok
        var C = [];
        var TC = []; // ok

        var offsetTatoos = 0;
        iF = 0;
        // iteration sur les faces
        while(iF<nbF) {    
            const R = _obj.range[iF];

            // MAJ avec extrusion des buffers des positions et normals et coordonnées de texture
         //   trace("MAJ buffer positions et normale face:" + iF + ", range:[" + R[0] + ", " + R[1] + "]");
            for (var iPt=R[0];iPt < R[1]; iPt++) {
                const i = iPt * 3; 

                var _MScale = gl.matScaleView;

                const x0 = positions[i]; 
                const y0 = positions[i + 1];
                const z0 = positions[i + 2];
               
                var x = x0,y = y0, z = z0;  // coord pti
                const nx0 = -(normals[i]);     
                const ny0 = -(normals[i + 1]); 
                const nz0 = -(normals[i + 2]);
                var nx = nx0, ny = ny0, nz =nz0; // normal pti

                const iptx = Math.min(iPt+1,R[1] -1), 
                        ix = iptx*3;
                const xx0 = positions[ix];const yx0 = positions[ix +1];const zx0 = positions[ix+2];
                var xx = xx0, yx = yx0, zx = zx0;   // coord pti+1, "x" for next

                const iptp = Math.max(iPt-1,R[0]); ip = iptp*3;
                const xp0 = positions[ip];const yp0 = positions[ip +1];const zp0 = positions[ip+2];
                var xp = xp0, yp = yp0, zp = zp0;   // coord pti-1, "p" for previous

                var matextr = [[1,0,0,(nx*_e)],[0,1,0,(ny*_e)],[0,0,1,(nz*_e)],[0,0,0,1]];
                // coord pti extruded, "e" for extruded
                const xe = getX_prodMatriceVecteur(matextr, x0,y0,z0); 
                const ye = getY_prodMatriceVecteur(matextr, x0,y0,z0); 
                const ze = getZ_prodMatriceVecteur(matextr, x0,y0,z0); 
                
                // normal vector for pti in side face formed with next pti = vectorial_product(vector pti>ptix, vector extrusion)
                const vectNc2 = prodVect3([xx-x,yx-y,zx-z],[xe-x,ye-y,ze-z]);

                // normal vector for pti in side face formed with previous pti = vectorial_product(vector pti->ptip, vector extrusion)
                const vectNc1 = prodVect3([x-xp,y-yp,z-zp],[xe-x,ye-y,ze-z]);

                const itc = iPt*2; 
                const xtc = textureCoordinates[itc] + frontXoffset; const ytc = textureCoordinates[itc+1] + frontYoffset;
                const xtce = textureCoordinates[itc] + backXoffset; const ytce = textureCoordinates[itc+1] + backYoffset;
                const xtcs = sideXoffset + 0.001; const ytcs = sideYoffset + 0.001;

            //    trace("Po:[" + x + ", " + y + ", " + z + " ]");
             //   trace("Pe:[" + xe + ", " + ye + ", " + ze + " ]");
                P.push(x);P.push(y);P.push(z);      N.push(nx); N.push(ny); N.push(nz);                         TC.push(xtc); TC.push(ytc);
                P.push(xe);P.push(ye);P.push(ze);   N.push(-(nx)); N.push(-(ny)); N.push(-(nz));                TC.push(xtce); TC.push(ytce);
                P.push(x);P.push(y);P.push(z);      N.push(vectNc1[0]); N.push(vectNc1[1]); N.push(vectNc1[2]); TC.push(xtcs);TC.push(ytcs);
                P.push(x);P.push(y);P.push(z);      N.push(vectNc2[0]); N.push(vectNc2[1]); N.push(vectNc2[2]); TC.push(xtcs);TC.push(ytcs);
                P.push(xe);P.push(ye);P.push(ze);   N.push(vectNc1[0]); N.push(vectNc1[1]); N.push(vectNc1[2]); TC.push(xtcs);TC.push(ytcs);
                P.push(xe);P.push(ye);P.push(ze);   N.push(vectNc2[0]); N.push(vectNc2[1]); N.push(vectNc2[2]); TC.push(xtcs);TC.push(ytcs);
            }

            const indiceOff = _obj.indiceOffset[iF];
            const VC = _obj.vertexCounts[iF];
            _obj.vertexCountsS.length = _obj.vertexCounts.length;

            // MAJ avec extrusion du Buffer des indices
       //     trace("MAJ buffer indices, face:" + iF + ", indOff:" + indiceOff + ", VCf:" + VC);
            _obj.indiceOffsetR[iF] = I.length;
            for (var iv=indiceOff;iv<VC+indiceOff;iv++) I.push(indices[iv] * 6+offsetTatoos);
            
            _obj.indiceOffsetV[iF] = I.length;
            for (var iv=indiceOff;iv<VC+indiceOff;iv++) I.push(indices[iv] * 6 + 1+offsetTatoos);

            var startS = I.length;
            _obj.indiceOffsetS[iF] = startS;
            for (var iPt=R[0];iPt < R[1]-1; iPt++) {
                const ipo = iPt*6+offsetTatoos; 
                const ipox=(iPt+1)*6+offsetTatoos; // next po
                const ipoc2 = ipo+3;  
                const ipec2=ipo+5;
                const ipoxc1 = ipox+2;
                const ipexc1 = ipox+4;
                I.push(ipoc2);I.push(ipec2);I.push(ipoxc1);
                I.push(ipec2);I.push(ipoxc1);I.push(ipexc1);
            }
            // 2 derniers triangles 
                const ipo = (R[1]-1)*6+offsetTatoos; 
                const ipox= R[0]*6+offsetTatoos; // next po
                const ipoc2 = ipo+3;  
                const ipec2=ipo+5;
                const ipoxc1 = ipox+2;
                const ipexc1 = ipox+4;
                I.push(ipoc2);I.push(ipec2);I.push(ipoxc1);
                I.push(ipec2);I.push(ipoxc1);I.push(ipexc1);
            
            _obj.vertexCountsS[iF] = I.length - startS;

            iF++;
        }
        trace("Calculation of extude buffers postions, normals, indices and texture coord !",0);
        const verticeOffset = gl.bufferContener.gP.length / 3;
        trace("verticeOffset:"+verticeOffset,0);
        gl.bufferContener.gP = gl.bufferContener.gP.concat(P);
        gl.bufferContener.gN = gl.bufferContener.gN.concat(N);
    //    trace("buffCont.gI len:" + gl.bufferContener.gI.length + ", isArray:" + Array.isArray(gl.bufferContener.gI) + ", concat I.len:" + I.length,0);
        var aI =[];
        I.forEach(i => aI.push(i+verticeOffset));
        gl.bufferContener.gI = gl.bufferContener.gI.concat(aI);
        gl.bufferContener.gTC = gl.bufferContener.gTC.concat(TC);

     //   trace("buffCont gI len after concat:" + gl.bufferContener.gI.length,0);

        trace(aI,0);
        _obj.vertexCount = I.length;
        return true;
    }

    var tmplMatP = [], tmplMatN=[];
    LMatResult.forEach(lm => lm.forEach(m => {tmplMatP.push(m);tmplMatN.push(getRotMatrix(m))}));

// initialisation du bufferContener
    if (gl.bufferContener.init) {
        initMatIndexes(struct_obj,nbFragmentsPli);
        trace(tmplMatP,0);        
        initStructBuffers(struct_obj);
    }
    // glMatP et glMatN initialise a chaque frame   
    gl.bufferContener.glMatP = gl.bufferContener.glMatP.concat(tmplMatP);
    gl.bufferContener.glMatN = gl.bufferContener.glMatN.concat(tmplMatN);


    var sizeOfPos = (struct_obj.positions.length*6);

    return struct_obj.vertexCount;
}

function getTransformPosBuffer(bufToTrans,lMat,lIndMat) {
    trace("getTransformPosBuffer",4);
    trace(bufToTrans,4);
    trace(lMat,4);
    trace(lIndMat,4);
    var j=0;
    const len = bufToTrans.length;
    const maxind = len / 3;
    var nbuf = [];
    nbuf.length = len;
    for (;j<maxind;j++) {
        const i=j*3
        const x=bufToTrans[i], y=bufToTrans[i+1], z=bufToTrans[i+2];
        const goodIndex=parseInt(j/6);
        const matIndex=lIndMat[goodIndex];
        const Mres = lMat[matIndex];
     //   trace("j:"+j+", i:"+i+", gi:"+goodIndex+", mi:"+matIndex,0);
        const nx = getX_prodMatriceVecteur(Mres,x,y,z);
        const ny = getY_prodMatriceVecteur(Mres,x,y,z);
        const nz = getZ_prodMatriceVecteur(Mres,x,y,z);
        nbuf[i] = nx; nbuf[i+1] = ny; nbuf[i+2] = nz;
    }
    return nbuf;
}

function getTransformPosBufferSubset(bufToTrans,lMat,lIndMat) {
    trace("getTransformPosBufferSubset",3);
    trace(bufToTrans,3);
    trace(lMat,3);
    trace(lIndMat,3);
    var j=0;
    const len = bufToTrans.length;
    const maxind = parseInt(len / 3 / 6);
    var nbuf = [];
    nbuf.length = parseInt(len / 6); // non extrude soit 1/6
    for (;j<maxind;j++) {
        const i=j*3*6;
        const ni=j*3;
        const x=bufToTrans[i], y=bufToTrans[i+1], z=bufToTrans[i+2];
        const goodIndex=parseInt(j);
        const matIndex=lIndMat[goodIndex];
        const Mres = lMat[matIndex];
     //   trace("j:"+j+", i:"+i+", gi:"+goodIndex+", mi:"+matIndex,0);
        const nx = getX_prodMatriceVecteur(Mres,x,y,z);
        const ny = getY_prodMatriceVecteur(Mres,x,y,z);
        const nz = getZ_prodMatriceVecteur(Mres,x,y,z);
        nbuf[ni] = nx; nbuf[ni+1] = ny; nbuf[ni+2] = nz;
    }
    return nbuf;
}

//
function initBuffers(gl,struct_obj_array,sequences,indexSeq,timeInSeq,deltaTime) {

  var positions = [];
  var normals = [];
  var colors = [];
  var indices = [];
  var textureCoordinates = [];

  if (gl.bufferContener == null) gl.bufferContener = {gP : [], gN : [], gI : [], gTC : [], gPtIMat : [], glMatP : [], glMatN : [],init : true};
    gl.bufferContener.glMatP = [];
    gl.bufferContener.glMatN = [];

    const nbObj = struct_obj_array.length;
    trace("nombre d'objet=" + nbObj);
    var curInd = 0;
  // remplissage des buffers
    while(curInd<nbObj) {
        const local_obj = struct_obj_array[curInd];
        trace("curInd:" + curInd);
        // trace(local_obj);
        getBuffersForStructGroup(gl,local_obj,sequences,indexSeq,timeInSeq);
        curInd++;
        // trace("curInd:" + curInd);
    }
    gl.bufferContener.init = false;
 /*   trace("positions len:" + positions.length + " / bufCont.gP len:" + gl.bufferContener.gP.length,0);
    trace("normals len:" + normals.length + " / bufCont.gN len:" + gl.bufferContener.gN.length,0);
    trace("indices len:" + indices.length + " / bufCont.gI len:" + gl.bufferContener.gI.length,0);
    trace("textureCoord len:" + textureCoordinates.length + " / bufCont.gTC len:" + gl.bufferContener.gTC.length,0) */
    var posSubSet = getTransformPosBufferSubset(gl.bufferContener.gP,gl.bufferContener.glMatP,gl.bufferContener.gPtIMat);
    normals = getTransformPosBuffer(gl.bufferContener.gN,gl.bufferContener.glMatN,gl.bufferContener.gPtIMat);
    indices = gl.bufferContener.gI;
    textureCoordinates = gl.bufferContener.gTC; 

// calcul de la matrice de transformation pour WebGL (scale et translation pour centrer l'objet)
  trace(posSubSet,4);
  var minx = posSubSet[0];
  var maxx = minx;
  var miny = posSubSet[1];
  var maxy = miny;
  var minz = posSubSet[2];
  var maxz = minz;
  var j=0;
  var maxj = posSubSet.length / 3;

  for (; j<maxj; j++) {
      const i = j * 3;
      const x = posSubSet[i];
      const y = posSubSet[i + 1];
      const z = posSubSet[i + 2];
    //  trace("x:"+x+", y:"+y+", z:"+z,0);
      minx = Math.min(minx,x);
      maxx = Math.max(maxx,x);
      miny = Math.min(miny,y);
      maxy = Math.max(maxy,y);
      minz = Math.min(minz,z);
      maxz = Math.max(maxz,z);
  }
  trace("range x,y,z for subset:",3);
  trace("x%(" + minx + " .. " + maxx + ")",3);
  trace("y%(" + miny + " .. " + maxy + ")",3);
  trace("z%(" + minz + " .. " + maxz + ")",3);
  

  const sclx = 2.0 / Math.max(1,(maxx - minx));
  const scly = 2.0 / Math.max(1,(maxy - miny));
  const sclz = 2.0 / Math.max(1,(maxz - minz));
  const s = Math.min(sclx,scly,sclz);
  const scaleVector = [s,-(s),s];
  trace(s);
  
 // const Rcaminit = getRotXMatrix(Math.PI / 4);
  const Rcam = getRotYMatrix(deltaTime * 0.1);

 /* const Tcam = [    [1.0, 0.0, 0.0, 0.0],
                    [0.0, 1.0, 0.0, 0.0],
                    [0.0, 0.0, 1.0, 0.0],
                    [0.0, 0.0, 0.0, 1.0]
                ]
*/
  const RTCam = Rcam; // prodMatriceArray([Rcaminit,Rcam,Tcam]);

  const S = [ [s,   0.0,    0.0,  0.0],
              [0.0, -(s),   0.0,  0.0],
              [0.0, 0.0,    s,    0.0],
              [0.0, 0.0,    0.0,  1.0]
            ];

    const translateVector = [-(minx +maxx) / 2.0,-(miny +maxy) / 2.0,-(minz +maxz) / 2.0];

    const T = [ [1.0,   0.0,  0.0,  translateVector[0]],
                [0.0,   1.0,  0.0,  translateVector[1]],
                [0.0,   0.0,  1.0,  translateVector[2]],
                [0.0,   0.0,  0.0,  1.0]
                ];

//    const ST = prodMatriceArray(RTCam,S,T);
 //   const ST = prodMatriceArray([Rcam,S,T]);
    const ST = prodMatrice(S,T);

    trace(S,3);
    trace(T,3);
    trace(ST,3);

// MAJ buffer des positions
    nbufMat = [];
    gl.bufferContener.glMatP.forEach(m => nbufMat.push(prodMatrice(ST,m)));
    positions = getTransformPosBuffer(gl.bufferContener.gP,nbufMat,gl.bufferContener.gPtIMat);

  // Créer un tampon des positions
  const positionBuffer = gl.createBuffer();

  // Définir le positionBuffer comme étant celui auquel appliquer les opérations
  // de tampon à partir d'ici.
  gl.bindBuffer(gl.ARRAY_BUFFER, positionBuffer);

  // Passer maintenant la liste des positions à WebGL pour construire la forme.
  // Nous faisons cela en créant un Float32Array à partir du tableau JavaScript,
  // puis en l'utilisant pour remplir le tampon en cours.
  gl.bufferData(gl.ARRAY_BUFFER,
                new Float32Array(positions),
                gl.STATIC_DRAW);

    // normals
    const normalBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, normalBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(normals), gl.STATIC_DRAW);

  // Conversion du tableau des couleurs en une table pour tous les sommets
    const colorBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, colorBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(colors), gl.STATIC_DRAW);

    // Gestion des textures
    const textureCoordBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, textureCoordBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoordinates),gl.STATIC_DRAW);

 // buffer des index
    const indexBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, indexBuffer);

  // Ce tableau définit chaque face comme deux triangles, en utilisant les
  // indices dans le tableau des sommets pour spécifier la position de chaque
  // triangle.

  // Envoyer maintenant le tableau des éléments à GL

    gl.bufferData(gl.ELEMENT_ARRAY_BUFFER,
          new Uint16Array(indices), gl.STATIC_DRAW);

  return {
    position: positionBuffer,
    color: colorBuffer,
    textureCoords: textureCoordBuffer,
    indices: indexBuffer,
    normal: normalBuffer,
    scale: scaleVector,
    translate: translateVector
  };
}

/***************************************************************************************
 * 
 * Texture loading
 * 
 * ************************************************************************************/

var renderering = false;

//
// Initialiser une texture et charger une image.
// Quand le chargement d'une image est terminé, la copier dans la texture.

function finishLoadTexture(gl, nurl, option) {
//    trace("finishLoadTexture nurl: " + nurl);
    trace("finishLoadTexture... ",0);
    trace(nurl);
    const texture = gl.createTexture();
    gl.bindTexture(gl.TEXTURE_2D, texture);

    // Du fait que les images doivent être téléchargées depuis l'internet,
    // il peut s'écouler un certain temps avant qu'elles ne soient prêtes.
    // Jusque là, mettre un seul pixel dans la texture, de sorte que nous puissions
    // l'utiliser immédiatement. Quand le téléchargement de la page sera terminé,
    // nous mettrons à jour la texture avec le contenu de l'image.
    const level = 0;
    const internalFormat = gl.RGBA;
    const width = 1;
    const height = 1;
    const border = 0;
    const srcFormat = gl.RGBA;
    const srcType = gl.UNSIGNED_BYTE;
    const pixel = new Uint8Array([255, 0, 255, 0]); //  255]);  
    gl.texImage2D(gl.TEXTURE_2D, level, internalFormat,
                width, height, border, srcFormat, srcType,
                pixel);

    const image = new Image();
    image.crossOrigin = "anonymous";

    image.onerror = function() {
        trace("failed to load image " + image);
    }
    image.onload = function() {
        trace("load img width:" + image.width + ", height:" + image.height,0);
        trace(image.src);
        texture.width = image.width;
        texture.height = image.height;
        trace("texture width:" + texture.width + ", height:" + texture.height);
        gl.bindTexture(gl.TEXTURE_2D, texture);
        gl.texImage2D(gl.TEXTURE_2D, level, internalFormat,
                      srcFormat, srcType, image);

        // WebGL1 a des spécifications différentes pour les images puissances de 2
        // par rapport aux images non puissances de 2 ; aussi vérifier si l'image est une
        // puissance de 2 sur chacune de ses dimensions.
        if (/* option === 2 && */ isPowerOf2(image.width) && isPowerOf2(image.height)) {
            // Oui, c'est une puissance de 2. Générer les mips.
            gl.generateMipmap(gl.TEXTURE_2D); // TEXTURE_CUBE_MAP
  //          gl.generateMipmap(gl.TEXTURE_CUBE_MAP); // TEXTURE_CUBE_MAP
        } else 
         {  trace("pas puissance de 2 !!!");
            // Non, ce n'est pas une puissance de 2. Désactiver les mips et définir l'habillage
            // comme "accrocher au bord"
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
        }
        if (renderering == false && typeof gl.renderCallback == "function") {renderering = true; requestAnimationFrame(gl.renderCallback);}
    };
    trace("try to load image ...");
    const test = "data:image/svg+xml;<svg viewBox='-10 -10 220 120' xmlns='http://www.w3.org/2000/svg'><polygon fill-rule='nonzero' stroke='red' points='50,0 21,90 98,35 2,35 79,90'/></svg>"
//    image.src = test; // nurl;
    image.src = nurl;
  //  image.setAttribute("src", nurl);
    return texture;
}


//
function loadTexture(gl, url, option) {
    const $S = jQuery;

  const sub3 = url.substring(0,4);

  trace("loadTexture(" + option + "), substring(0,4) : " + sub3,0);
  trace(url);
    var nurl = url;

    if (sub3 == "<svg") {
        trace("SVG URL !!!!!",0);
        const nurl = "data:image/svg+xml," + url; 
        var texture = gl.createTexture();
        gl.bindTexture(gl.TEXTURE_2D, texture);

        // Du fait que les images doivent être téléchargées depuis l'internet,
        // il peut s'écouler un certain temps avant qu'elles ne soient prêtes.
        // Jusque là, mettre un seul pixel dans la texture, de sorte que nous puissions
        // l'utiliser immédiatement. Quand le téléchargement de la page sera terminé,
        // nous mettrons à jour la texture avec le contenu de l'image.
        const level = 0;
        const internalFormat = gl.RGBA;
        const width = 1;
        const height = 1;
        const border = 0;
        const srcFormat = gl.RGBA;
        const srcType = gl.UNSIGNED_BYTE;
        const pixel = new Uint8Array([240, 220, 240, 255]); //  255]);  
        gl.texImage2D(gl.TEXTURE_2D, level, internalFormat,
                    width, height, border, srcFormat, srcType,
                    pixel);
        
        var divsvgElement = document.createElement('div');

        divsvgElement.setAttribute('id', 'divsvgElement');

        $S(divsvgElement).html(url);

      //  trace("divsvgElement : " + $S(divsvgElement).html());

        var limage1 = $S(divsvgElement).find("image");
        var limage = [];
        limage1.each(function(){
            if (this.href.baseVal.substring(0,4) == "http") limage.push(this);
        })
    //    trace(limage);

        countLoaded = limage.length;
        trace("nb image to load : " + countLoaded,0);

        var lmapUrlBase64 = [];

        if (countLoaded > 0) {
            $S(limage).each(function() { 
             //   trace(this);
                const _href = this.href.baseVal;
            //    trace(_href);
                svgImg = this;
                if (_href.substring(0,4) == "http") {
                    var _url = "";
                    var img =  new Image();
                    img.crossOrigin = "anonymous";
                    img.onerror = function() {
                        trace("failed to load image" + img,0);
                        lmapUrlBase64.push([_href,"data:image/svg+xml,<svg><rect x=\"0\" y=\"0\" width=\"10\" height=\"10\"/></svg>"]);
                        countLoaded--;// body...
                        if (countLoaded == 0) onAllImageLoad(gl,texture,option,divsvgElement,lmapUrlBase64);
                    };
                    img.onload = function(){
                        trace("load : " + _href,0);
                        var canvas = document.createElement("canvas");
                        canvas.width = this.width;
                        canvas.height = this.height;
                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(this, 0, 0);
                        _url = canvas.toDataURL('image/png',1.0);
                    //    trace(_url);
                        lmapUrlBase64.push([_href,_url])
                     //   $S(svgImg).attr('href', _url);
                   //     trace(svgImg);
                        // this.href.animeVal = _url;
                        countLoaded--;
                        if (countLoaded == 0) onAllImageLoad(gl,texture,option,divsvgElement,lmapUrlBase64);
                    }  
                    img.src = _href;            
                } else {
                    lmapUrlBase64.push([_href,_href]);
                    countLoaded--;
                    if (countLoaded == 0) onAllImageLoad(gl,texture,option,divsvgElement,lmapUrlBase64);
                }
            });
        } else {
            
            var svgString = new XMLSerializer().serializeToString($S(divsvgElement).find("svg")[0]);

            var decoded = unescape(encodeURIComponent(svgString));
            // Now we can use btoa to convert the svg to base64
            var base64 = btoa(decoded);
            var imgSource = "data:image/svg+xml;base64," + base64;
 
            const image = new Image();
            image.crossOrigin = "anonymous";
            image.onerror = function() {
            trace("unable to load image" + image,0);
            //    afterLoadTexture(gl,texture,image,true,option);
                if (renderering == false && typeof gl.renderCallback == "function") {renderering = true; requestAnimationFrame(gl.renderCallback);}
            };
            image.onload = function(){afterLoadTexture(gl,texture,image,true,option);}
            image.src = imgSource;
//            texture = finishLoadTexture(gl,imgSource,option);
        }
        trace("Got a texture to return !!!!!!!!!!!!!!!!!!!!!!!");
        trace(texture);
        return texture;

    } else return finishLoadTexture(gl,url,option);
}

function onAllImageLoad(gl,texture,option,divsvgElement,lmapUrlBase64) {
    const $S = jQuery;
   trace("onAllImageLoad, trace du svg !!!!!!!!!!!!!!!!!!!!!!!!",0);
    // Serialize the svg to string
   trace($S(divsvgElement)[0].getAttribute("width"),0);
    var svgString = new XMLSerializer().serializeToString($S(divsvgElement).find("svg")[0]);

//    trace(lmapUrlBase64);
    for(var i=0; i<lmapUrlBase64.length;i++) {
//         trace("replace : " + lmapUrlBase64[i][0]);
        svgString = svgString.replaceAll(lmapUrlBase64[i][0],lmapUrlBase64[i][1]);
    }

    trace(svgString,0);

    // Remove any characters outside the Latin1 range
    var decoded = unescape(encodeURIComponent(svgString));

    // Now we can use btoa to convert the svg to base64
    var base64 = btoa(decoded);

//                        var imgSource = `data:image/svg+xml;base64,${base64}`;
    var imgSource = "data:image/svg+xml;base64," + base64;

//    trace(imgSource);

    const image = new Image();
    image.crossOrigin = "anonymous";

    image.onerror = function() {
        trace("failed to load image" + image);
        if (renderering == false && typeof gl.renderCallback == "function") {renderering = true; requestAnimationFrame(gl.renderCallback);}
    };
    image.onload = function(){afterLoadTexture(gl,texture,image,true,option);}
    image.src = imgSource;
}

function afterLoadTexture(gl,texture,image,callRender,option) {
    trace("afterLoad Atlas img ... width:" + image.width + ", height:" + image.height,0);
    // trace(image.src,0);
    if (image.width > 0 && image.height == 0) image.height = image.width;
    image.width = 2048 * 1;
    image.height = 2048 * 1;
    texture.width = image.width;
    texture.height = image.height;
 //   trace("texture width:" + texture.width + ", height:" + texture.height);

    const level = 0;
    const internalFormat = gl.RGBA;
    const width = 1;
    const height = 1;
    const border = 0;
    const srcFormat = gl.RGBA;
    const srcType = gl.UNSIGNED_BYTE;
    const pixel = new Uint8Array([255, 0, 255, 0]); //  255]);  

    gl.bindTexture(gl.TEXTURE_2D, texture);
    gl.texImage2D(gl.TEXTURE_2D, level, internalFormat,
                  srcFormat, srcType, image);

    // WebGL1 a des spécifications différentes pour les images puissances de 2
    // par rapport aux images non puissances de 2 ; aussi vérifier si l'image est une
    // puissance de 2 sur chacune de ses dimensions.
    if (option === 2 && isPowerOf2(image.width) && isPowerOf2(image.height)) {
        // Oui, c'est une puissance de 2. Générer les mips.
        gl.generateMipmap(gl.TEXTURE_2D);
    } else {  
        trace("pas puissance de 2 !!!");
        // Non, ce n'est pas une puissance de 2. Désactiver les mips et définir l'habillage
        // comme "accrocher au bord"
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
    }
    if (callRender && renderering == false && typeof gl.renderCallback == "function") {renderering = true; requestAnimationFrame(gl.renderCallback);}
}

//
// Initialiser une texture et charger une image.
// Quand le chargement d'une image est terminé, la copier dans la texture.
//
function loadTexture_old(gl, url, option) {
  const sub3 = url.substring(0,4);
  trace("loadTexture(" + option + "), substring(0,4) : " + sub3);
  trace(url);
    var nurl = url;

    if (sub3 == "<svg") {
      const replacedSVG = url; /*.replace(/#/g, '%23')
                           .replace(/"/g, "'")
                           .replace(/&/g, '&amp;'); */

        nurl = "data:image/svg+xml," + replacedSVG;
        trace("nurl : " + nurl);
    }

  const texture = gl.createTexture();
  gl.bindTexture(gl.TEXTURE_2D, texture);

  // Du fait que les images doivent être téléchargées depuis l'internet,
  // il peut s'écouler un certain temps avant qu'elles ne soient prêtes.
  // Jusque là, mettre un seul pixel dans la texture, de sorte que nous puissions
  // l'utiliser immédiatement. Quand le téléchargement de la page sera terminé,
  // nous mettrons à jour la texture avec le contenu de l'image.
  const level = 0;
  const internalFormat = gl.RGBA;
  const width = 1;
  const height = 1;
  const border = 0;
  const srcFormat = gl.RGBA;
  const srcType = gl.UNSIGNED_BYTE;
  const pixel = new Uint8Array([255, 0, 255, 0]); //  255]);  
  gl.texImage2D(gl.TEXTURE_2D, level, internalFormat,
                width, height, border, srcFormat, srcType,
                pixel);

  const image = new Image();
  image.crossOrigin = "anonymous";
  
  image.onerror = function() {trace("failed to load image" + image)};
  image.onload = function() {
    trace("load img " + image.src + ", width:" + image.width + ", height:" + image.height);
    texture.width = image.width;
    texture.height = image.height;
    trace("texture width:" + texture.width + ", height:" + texture.height);
    gl.bindTexture(gl.TEXTURE_2D, texture);
    gl.texImage2D(gl.TEXTURE_2D, level, internalFormat,
                  srcFormat, srcType, image);

    // WebGL1 a des spécifications différentes pour les images puissances de 2
    // par rapport aux images non puissances de 2 ; aussi vérifier si l'image est une
    // puissance de 2 sur chacune de ses dimensions.
    if (option === 2 && isPowerOf2(image.width) && isPowerOf2(image.height)) {
        // Oui, c'est une puissance de 2. Générer les mips.
        gl.generateMipmap(gl.TEXTURE_2D);
    } else 
     {
        // Non, ce n'est pas une puissance de 2. Désactiver les mips et définir l'habillage
        // comme "accrocher au bord"
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
    }
/* 
    // gl.NEAREST est aussi permis, au lieu de gl.LINEAR, du fait qu'aucun ne fait de mipmap.
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
    // Empêcher l'habillage selon la coordonnée s (répétition).
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
    // Empêcher l'habillage selon la coordonnée t (répétition).
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
*/
 //   if (renderering == false) {renderering = true; requestAnimationFrame(gl.renderCallback);}
  };
     image.src = nurl;

  return texture;
}

function isPowerOf2(value) {
  return (value & (value - 1)) == 0;
}

//
// Initialiser une texture et charger une image.
// Quand le chargement d'une image est terminé, la copier dans la texture.
//
function getTextureFromColor(gl, color) {
  trace("getTextureFromColor");
  trace(color);
  const texture = gl.createTexture();
  gl.bindTexture(gl.TEXTURE_2D, texture);

  // Du fait que les images doivent être téléchargées depuis l'internet,
  // il peut s'écouler un certain temps avant qu'elles ne soient prêtes.
  // Jusque là, mettre un seul pixel dans la texture, de sorte que nous puissions
  // l'utiliser immédiatement. Quand le téléchargement de la page sera terminé,
  // nous mettrons à jour la texture avec le contenu de l'image.
  const level = 0;
  const internalFormat = gl.RGBA;
  const width = 1;
  const height = 1;
  const border = 0;
  const srcFormat = gl.RGBA;
  const srcType = gl.UNSIGNED_BYTE;
  const pixel = new Uint8Array(color);  // bleu opaque
  gl.texImage2D(gl.TEXTURE_2D, level, internalFormat,
                width, height, border, srcFormat, srcType,
                pixel);

    // Oui, c'est une puissance de 2. Générer les mips.    
    gl.generateMipmap(gl.TEXTURE_2D);

  return texture;
}


function loadAllTexture(gl,textures_url,textures_option) {
    var textures = []
    renderering = false;
    for(i=0;i<textures_url.length;i++) {
        textures.push(loadTexture(gl,textures_url[i], textures_option[i]));
    }
    return textures;
}

function setTextureToStructObjGroup(gl, struct_obj, textures) {
    if (Array.isArray(struct_obj.things)) {
        struct_obj.things.forEach(function(local_obj){setTextureToStructObjGroup(gl, local_obj, textures)});

    } else setTextureToStructObj(gl, struct_obj, textures);
}

function setTextureToStructObj(gl, struct_obj, textures) {
    trace("setTextureToStructObj(" + struct_obj.tag + "), textures.length : " + textures.length,0);
    const tiR = struct_obj.texIndexR;
    if (tiR > -1 && tiR < textures.length) struct_obj.texObjR = textures[tiR];
    const tiV = struct_obj.texIndexV;
    if (tiV > -1 && tiV < textures.length) struct_obj.texObjV = textures[tiV];
    const tiS = struct_obj.texIndexS;
    if (tiS > -1 && tiS < textures.length) struct_obj.texObjS = textures[tiS];

    var i=0;
    for(;i<struct_obj.texIndex.length;i++) {
        const ti = struct_obj.texIndex[i];
        if (ti > -1 && ti < textures.length) {
           trace("face:" + i + ", affectation texture index:" + ti);
            struct_obj.texObj[i] = textures[ti];
        } else {
            trace("face:" + i + ",ti:" + ti + ", affectation texture couleur unie");
            const colorTex = getTextureFromColor(gl,struct_obj.defColors[i]);
            struct_obj.texObj[i] = colorTex;
        }
        trace(struct_obj.texObj[i]);
    }
    if (Array.isArray(struct_obj.tatoos)) {
        const len = struct_obj.tatoos.length;
        trace(struct_obj.tatoos);
        for(i=0; i < len; i++) {
            const _tatoo = struct_obj.tatoos[i];
            const ti = _tatoo.texId;
            if (ti > -1 && ti < textures.length) {
               trace("tatoo:" + i + ", affectation texture index:" + ti + ", Htex:" + textures[ti].height + ", Ltex:" + textures[ti].width);
                struct_obj.tatoos[i].texObj = textures[ti];
            }
        }
    }
}

function initTextures(gl, struct_obj_array,textures_url,textures_option) {
    trace("initTextures");
    const textures = loadAllTexture(gl,textures_url,textures_option);
    var iS = 0;
    while(iS<struct_obj_array.length) {
        setTextureToStructObjGroup(gl,struct_obj_array[iS],textures);
        iS++;
    }
    return textures;
}

/************************************************************************************
 * 
 * Draw on canvas
 * 
 * **********************************************************************************/


function drawFace(gl,programInfo,curVCount,curOffset,text,texId) {
   const type = gl.UNSIGNED_SHORT;
//  gl.drawArrays(gl.TRIANGLE_STRIP, offset, vertexCount); // carré 2D
    
    const TID = Math.max(0,texId);

    gl.activeTexture(gl.TEXTURE0 + TID);

    // Lier la texture à l'unité de texture 0
    gl.bindTexture(gl.TEXTURE_2D, text);

    // Indiquer au shader que nous avons lié la texture à l'unité de texture 0
    gl.uniform1i(programInfo.uniformLocations.uSampler, TID);

    gl.drawElements(gl.TRIANGLES, curVCount, type, curOffset);
}

function drawFace1(gl, programInfo,ooffset, IO, VC, text,texId) {
    trace("drawFace1(" + ooffset + ", " + IO + ", " + VC + ", texId:" + texId + ")");
    trace(text);
    trace("nb Triangles to draw: " + gl.TRIANGLES);
    const nbBytes = 2;
    const curOffset = ooffset + IO * nbBytes;
    const curVCount = VC;
    drawFace(gl,programInfo,VC,curOffset,text,texId);
    return (IO + VC) * nbBytes;
}
function drawObj(gl,programInfo,obj,textures_obj,ooffset) {
        
    const nbBytes = 2;
    var maxOffset = 0;
    trace(">> drawObj:" + obj.tag);
 //   trace(ooffset);
    var maxOffset = 0;

    for(var j=0;j<obj.texObj.length;j++) {
        var texture = obj.texObj[j];
        var texId = obj.texIndex[j];
        trace(texture);
        if (texture === null) texture = textures_obj[0];

        if (obj.extrusion > 0.0) {
            var textTpl = texture; var textTplId = texId; 
            if (obj.texObjR != null) textTpl = obj.texObjR; 
            if (obj.texIndexR > -1)  textTplId = obj.texIndexR;
            maxOffset = Math.max(maxOffset,drawFace1(gl,programInfo,ooffset,obj.indiceOffsetR[j],obj.vertexCounts[j], textTpl, textTplId));
            
            if (obj.texObjV != null) textTpl = obj.texObjV; 
            if (obj.texIndexV > -1)  textTplId = obj.texIndexV;
            maxOffset = Math.max(maxOffset,drawFace1(gl,programInfo,ooffset,obj.indiceOffsetV[j],obj.vertexCounts[j], textTpl, textTplId));
            
            if (obj.texObjS != null) textTpl = obj.texObjS; 
            if (obj.texIndexS > -1)  textTplId = obj.texIndexS;
            maxOffset = Math.max(maxOffset,drawFace1(gl,programInfo,ooffset,obj.indiceOffsetS[j],obj.vertexCountsS[j], textTpl, textTplId));

        } else maxOffset = Math.max(maxOffset,drawFace1(gl,programInfo,ooffset,obj.indiceOffset[j],obj.vertexCounts[j], texture, texId));
        
    }
    if (Array.isArray(obj.tatoos)) {
      //  gl.depthFunc(gl.ALWAYS);
 
        for (var itatoo=0;itatoo<obj.tatoos.length;itatoo++) {
            const _tatoo = obj.tatoos[itatoo];
            const tTatooId = _tatoo.texId;
            const tTatoo = textures_obj[tTatooId];
            const _tatooIndice = _tatoo.indice;
            const _tatooVC = _tatoo.VC;
            
            if (tTatoo != null && tTatooId > -1 && _tatooIndice > 0 && _tatooVC > 0) {
             //   trace("draw tatoo tex " + tTatooId + ", indice:" + _tatooIndice + ", VC:" + _tatooVC + " !!!!!!!");
            //    trace(tTatoo);
                maxOffset = Math.max(maxOffset,drawFace1(gl,programInfo,ooffset,_tatooIndice,_tatooVC, tTatoo, tTatooId));
            }
        }
    }

    trace("<< drawObj:" + obj.tag);
    trace("maxOffset:" + maxOffset + ", vertexCountx2:" + (obj.vertexCount * nbBytes));
    return maxOffset;
}
  //
function drawGroup(gl,programInfo,grp,textures_obj,ooffset) {
    trace(">> drawGroup:" + grp.tag);
    trace(ooffset);
    var maxOffset = 0;

    if (Array.isArray(grp.things)) {
        grp.things.forEach(function(obj){
            const curOffset = ooffset + maxOffset;
            trace("curOffset:" + curOffset);
            maxOffset += drawGroup(gl,programInfo,obj,textures_obj,curOffset);
        //    maxOffset = Math.max(maxOffset, drawGroup(gl,programInfo,obj,textures_obj,curOffset));
            trace("maxOffset:" + maxOffset);
            });
    } else if (Array.isArray(grp.texObj)) {
        maxOffset = drawObj(gl,programInfo,grp,textures_obj,ooffset);
    }

    trace("<< drawGroup:" + grp.tag);
    trace("maxOffset:" + maxOffset);
    return maxOffset;
}

function drawLayoutAtlas(gl,programInfo,grp,textures_obj,ooffset) {
    trace(">> drawLayoutAtlas:" + grp.tag + ", ooffset:" + ooffset);
   // trace(ooffset);
    var maxOffset = 0;

    const curVCount = grp.vertexCount;
    maxOffset = drawFace1(gl,programInfo,ooffset,0,curVCount,textures_obj[0],0);

    trace("<< drawLayoutAtlas:" + grp.tag);
    trace("maxOffset:" + maxOffset);
    return maxOffset;
}


/**************************************************************************************
 * 
 * Run 
 * 
 * ************************************************************************************/

//click
function runWebGl(canvas,scenes_arrays, preserveBuff){

    var scene_index = 0;
    var structures_objects = scenes_arrays.scene_things[0];
    var textures_url = scenes_arrays.scene_textures_url[0];
    var textures_option = scenes_arrays.scene_textures_options[0];
    var sequences = scenes_arrays.scene_sequences[0];

    var statesBuffer = [];

    function saveGrpSetup(grp){
        trace("saveGrpSetup",4);
        trace(grp,5);
        if (Array.isArray(grp)) grp.forEach(th => saveGrpSetup(th));
        else {
            if (Array.isArray(grp.Msetup)) {
                if (!Array.isArray(grp.MsetupBuffer)) grp.MsetupBuffer = [];
                const copMsetup = [];
                grp.Msetup.forEach(line => copMsetup.push(line.slice()));
                trace("push Msetup",5);
                grp.MsetupBuffer.push(copMsetup);
            }
            if (Array.isArray(grp.theta_cur_angle)) {
                if (grp.curAnglesBuffer == null) grp.curAnglesBuffer = [];
                grp.curAnglesBuffer.push(grp.theta_cur_angle.slice());
                trace("push cur angles",5);
            }
            if (Array.isArray(grp.things)) {
                grp.things.forEach(th => saveGrpSetup(th));
            }
        }
    }
    function reloadGrpSetup(grp,index){
        trace("reloadGrpSetup index:" + index,4);
        trace(grp,5);
        if (Array.isArray(grp)) grp.forEach(th => reloadGrpSetup(th,index));
        else {
            if (Array.isArray(grp.MsetupBuffer) && index < grp.MsetupBuffer.length) {
                grp.Msetup = [];
                grp.Msetups = [];
                grp.MsetupBuffer[index].forEach(line => grp.Msetup.push(line.slice()));
                trace("MAJ Msetup",5);
                grp.MsetupBuffer.length = index + 1;
            }
            if (Array.isArray(grp.curAnglesBuffer) && index < grp.curAnglesBuffer.length) {
                grp.theta_cur_angle = grp.curAnglesBuffer[index].slice();
                trace("MAJ cur angles",5);
                grp.curAnglesBuffer.length = index + 1;
            }
            if (Array.isArray(grp.things)) {
                grp.things.forEach(th => reloadGrpSetup(th,index));
            }
        }
    }

    if (preserveBuff === null) 
        preserveBuff = false;
    var alpha_channel_active = true; // true;
 //   if (preserveBuff) alpha_channel_active = false;
  // Initialisation du contexte WebGL
    var gl = canvas.getContext("webgl",{preserveDrawingBuffer: preserveBuff, alpha: alpha_channel_active, antialias: true, colorSpace: "srgb"});
    
    gl.currentSceneIndex = 0;
    gl.currentSequenceIndex = 0;
    gl.currentTimeInSequence = 0;
    gl.forceSaveState = true;

    function saveState(){
        gl.forceSaveState = false;
        var curBuffers = [];
        if (gl.endBuffers != null) curBuffers = gl.endBuffers;
        const newState = {
                            sceneIndex : gl.currentSceneIndex, 
                            sequenceIndex : gl.currentSequenceIndex, 
                            timeInSequence:gl.currentTimeInSequence,
                            buffers : curBuffers
                        };
        statesBuffer.push(newState);
        trace("saveState",0);
        trace(scenes_arrays,4);
        if (Array.isArray(scenes_arrays.scene_things)) 
            scenes_arrays.scene_things.forEach(function(grp){saveGrpSetup(grp)});
        trace(scenes_arrays,4);
        trace(statesBuffer,3);
    }

    function reloadState(index){
       trace("reloadState " + index + ", len states : " + statesBuffer.length,0);
       trace(statesBuffer,3);
        trace(scenes_arrays,3);
        if (index < statesBuffer.length) {
            const targState = statesBuffer[index];
            trace("sce ind : " + targState.sceneIndex,0);
            gl.currentSceneIndex = targState.sceneIndex;
            gl.currentSequenceIndex = targState.sequenceIndex;
            gl.currentTimeInSequence = targState.timeInSequence;
            gl.endBuffers = targState.buffers;
            if (Array.isArray(scenes_arrays.scene_things)) 
                scenes_arrays.scene_things.forEach(function(grp){reloadGrpSetup(grp,index)});
            statesBuffer.length = Math.min(index + 1,statesBuffer.length);
            trace(scenes_arrays,3);
        }
    }
    function reloadPrevState(){reloadState(statesBuffer.length-2);}
    
    gl.oldMouseX = 0;
    gl.oldMouseY = 0;

    gl.mdX = 0; // variation off mouse x relative to canvas width
    gl.mdY = 0;
    gl.mdZ = 0;

    gl.zplus = false;
    gl.zminus = false;

    gl.mDown = -1;
    gl.fullscreen = false;

    gl.mdX2 = 0;
    gl.mdY2 = 0;

    function setDeltaMousePos(nMX,nMY) {
        gl.mdX = (nMX - gl.oldMouseX) / canvas.width;
        gl.mdY = (nMY - gl.oldMouseY) / canvas.height;
    }
    function setDeltaMousePosRight(nMX,nMY) {
        gl.mdX2 = (nMX - gl.oldMouseX) / canvas.width;
        gl.mdY2 = (nMY - gl.oldMouseY) / canvas.width;
    }

    function setDeltaMousePosCtrl(nMX,nMY) {
        gl.mdZ = (gl.oldMouseX - nMX) / canvas.width;
    } 
    gl.deltaScrollX = 0;
    gl.deltaScrollY = 0;
    gl.oldScale = 0;
    gl.oldRotZ = 0;
    function setDeltaScroll(nMX,nMY,nMX1,nMY1) {
        const delta = Math.abs(nMX - nMX1);
        gl.deltaScrollY = (delta - gl.oldScale) / canvas.width;
        gl.oldScale = delta;
    }

    function setDeltaScroll2(nDX,nDY) {
        gl.deltaScrollX = nDX / canvas.width;
        gl.deltaScrollY = nDY / canvas.height;
    }

    canvas.style.zIndex = -1;
    canvas.style.pointerEvents = "none";
   // canvas.style = "position: absolute;";
    
    const canvas2D = document.createElement("canvas");
    var canvasRect = canvas.getBoundingClientRect();
    trace(canvasRect,0);

    canvas2D.setAttribute("id",canvas.id + "_2D");
    canvas2D.setAttribute("width",canvas.width);
    canvas2D.setAttribute("height",canvas.height);
    canvas2D.style = `position: absolute;z-index: 2;pointer-events: auto; left:${canvasRect.left+window.pageXOffset}px; top:${canvasRect.top+window.pageYOffset}px`;
    canvas.parentElement.appendChild(canvas2D);

    function onResize() {
        if (gl.fullscreen) {
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = Math.min(canvas.width / 1.2,canvas.parentElement.clientHeight);
            canvas2D.setAttribute("width",canvas.width);
            canvas2D.setAttribute("height",canvas.height);
            
            canvas2D.style = `position: absolute;z-index: 2;pointer-events: auto; left:0px; top:0px`;
        }
        else {
            trace("onresize not fullscreen",0);
            canvas.width = Math.min(canvas.parentElement.clientWidth * 0.95,800);
            canvas.height = canvas.width * 9 / 16; // Math.min(canvas.width / 1.2,canvas.parentElement.clientHeight);
            canvas2D.setAttribute("width",canvas.width);
            canvas2D.setAttribute("height",canvas.height);
    //        canvasRect = canvas.getBoundingClientRect();
    //        canvas2D.style = `position: absolute;z-index: 2;pointer-events: auto; left:${canvasRect.left+window.pageXOffset}px; top:${canvasRect.top + window.pageYOffset}px`;
            canvas2D.style = `position: absolute;z-index: 2;pointer-events: auto; left:${canvas.offsetLeft}px; top:${canvas.offsetTop}px`;
        }
        trace("onResize",0);
        trace(canvasRect,0);
        trace(canvas2D.getBoundingClientRect(),0);
        gl.mdX = 0.01;              // to trigger initGl
        onDeltaMouseInitGL(gl);     // force initGl on mouse change
        redrawScene();              // redraw Scene on canvas 3D
        gl.guiImg = getGuiImg();    // get new Gui Image, clear and redraw Gui on canvas 2D
        gl.mdX = 0;                 // init mouse delta mouve
        gl.mDown = -1;           // init mouse down trigger
    }

    window.addEventListener("load", function() {
       trace("windows load",0);
       onResize();
    });

    window.addEventListener("resize", function() {
       trace("windows resize",0);
       onResize();
    });

    trace("canvas2D : ",0);
    trace(canvas2D,0);

    const events = new Events(canvas2D); 
    const ctx2d = events.getContext();

    gl.ctx2D = ctx2d;
    gl.comment = "";

    function writeComment(gl, message){
     //   gl.ctx2D.font = "18pt Calibri";
     //   gl.ctx2D.fillText(message, 10, 550);
    }/*from   ww  w. j a  va 2 s  .co m*/

    function writeMessage(context, message){
    //    gl.comment = message;
   //     events.drawStage();
    //    context.font = "18pt Calibri";
    //    context.fillText(message, 10, 25);
    }/*from   ww  w. j a  va 2 s  .co m*/

    function guiZoomPlus(){
        trace("zoom plus",0);
        gl.zplus = true;
        if (gl.endBuffers != null) drawScene(gl, programInfo,gl.endBuffers,0,textures_obj);
    };

    function guiZoomMinus(){
        trace("zoom minus",0);
        gl.zminus = true;
        if (gl.endBuffers != null) drawScene(gl, programInfo,gl.endBuffers,0,textures_obj);
    };

    function guiToggleFullscreen(){
        trace("toggle fullscreen",0);
        toggleFullscreen();
    }

    function guiExitFullscreen(){
        trace("exit fullscreen",0);
        existCanvasFullscreen();
    }

    gl.renderPause = false;
    function guiPause(){
        trace("pause",0);
        gl.renderPause = true;
    };
    gl.startplay = true;
    gl.renderStop = false;
    function guiPlay(){
        trace("play",0);
        gl.renderPause = false;
        gl.renderStop = false;
        gl.startplay = true;
        if (renderering == false) {        
            renderering = true; 
            requestAnimationFrame(render);
        }
    };
    function guiStop(){
        trace("stop",0);
        gl.renderPause = true;
        reloadState(0);
        redrawScene();
        gl.renderStop = true;
        requestAnimationFrame(render);
        renderering = false;
    };
    function guiReverse(){
        trace("reverse",0);
        gl.renderPause = true;
        reloadPrevState();
        redrawScene();
        gl.renderStop = true;
        requestAnimationFrame(render);
        renderering = false;
    };
    function guiSpeedUp(){};
    function guiSpeedDown(){};

    var gGui = {
        zp : {handler : guiZoomPlus, x : 112, y : 75, w : 6, h : 6 },
        zm : {handler : guiZoomMinus, x : 112, y : 81, w : 6, h : 6 },
        tfs : {handler : guiToggleFullscreen, x : 112, y : 92, w : 6, h : 6 },
        efs : {handler : guiExitFullscreen, x : 104, y : 92, w : 6, h : 6 },
        pause : {handler : guiPause, x : 18, y : 92, w : 6, h : 6 },
        play : {handler : guiPlay, x : 26, y : 92, w : 6, h : 6 },
        stop : {handler : guiStop, x : 2, y : 92, w : 6, h : 6 },
        rev : {handler : guiReverse, x : 10, y : 92, w : 6, h : 6 },
        spx2 : {handler : guiSpeedUp, x : 34, y : 92, w : 6, h : 6 },
        spx4 : {handler : guiSpeedDown, x : 40, y : 92, w : 6, h : 6 } 
        };

    function checkClickOnGui(mcx,mcy){
        var mx = mcx / canvas.width * 120;
        var my = mcy / canvas.height * 100;
        trace(`checkClickOnGui mx:${mx}, my:${my}`,0);
        var BreakException = {};
        try {
            Object.entries(gGui).forEach(function([gKey,gObj]) {
    //        trace(gKey + ": x=" + gObj.x + ", y=" + gObj.y ,0);
              if (gObj.x <= mx && (gObj.x+gObj.w) >= mx && gObj.y <= my && (gObj.y+gObj.h) >= my) {
                gObj.handler();
                throw BreakException;
              }
            });
        } catch  (e) {if (e !== BreakException) throw e}
    }

    function getGuiImg() {
        var guicol = "white";
        const buttonOpacity = 0.8;
        const bgOpacity = 0.2;
        const bgColor = "white";
        const GUIsvg = `data:image/svg+xml,<svg width='${canvas.width}px' height='${canvas.height}px' viewBox='0 0 120 100' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'>
        <text x='5' y='20' fill='${guicol}' font-size='4pt'>${gl.comment}</text>
        <rect x='0' y='90' width='120' height='10' fill='${bgColor}' fill-opacity='${bgOpacity}'/>
        <rect x='${gGui.zp.x}' y='${gGui.zp.y}' width='${gGui.zp.w}' height='${gGui.zp.h}' fill='${bgColor}' fill-opacity='${bgOpacity}' stroke-width='0.0' stroke='${guicol}' stroke-opacity='${buttonOpacity}'/>
        <rect x='${(gGui.zp.x+gGui.zp.w/2-0.5)}' y='${gGui.zp.y+1}' width='1' height='${gGui.zp.h-2}' fill='${guicol}' fill-opacity='${buttonOpacity}'/>
        <rect x='${gGui.zp.x+1}' y='${gGui.zp.y+gGui.zp.h/2-0.5}' width='${gGui.zp.w-2}' height='1' fill='${guicol}' fill-opacity='${buttonOpacity}'/>
        <rect x='${gGui.zm.x}' y='${gGui.zm.y}' width='${gGui.zm.w}' height='${gGui.zm.h}' fill='${bgColor}' fill-opacity='${bgOpacity}' stroke-width='0.0' stroke='${guicol}' stroke-opacity='${buttonOpacity}'/>
        <rect x='${gGui.zm.x+1}' y='${gGui.zm.y+gGui.zm.h/2-0.5}' width='${gGui.zm.w-2}' height='1' fill='${guicol}' fill-opacity='${buttonOpacity}'/>

        <path d='M${gGui.tfs.x},${gGui.tfs.y} l${gGui.tfs.w/3},0 m${gGui.tfs.w/3},0 l${gGui.tfs.w/3},0 0,${gGui.tfs.h/3} m0,${gGui.tfs.h/3} l0,${gGui.tfs.h/3} ${-(gGui.tfs.w)/3},0 m${-(gGui.tfs.w)/3},0 l${-(gGui.tfs.w)/3},0 0,${-(gGui.tfs.h)/3} m0,${-(gGui.tfs.h)/3} l0,${-(gGui.tfs.h)/3}z' fill='none' stroke-width='1' stroke='${guicol}' stroke-opacity='${buttonOpacity}'/>
        <path d='M${gGui.efs.x},${gGui.efs.y} m0,${gGui.efs.h/3} h${gGui.efs.w/3} v${-(gGui.efs.h)/3} m${gGui.efs.w/3},0 v${gGui.efs.h/3} h${gGui.efs.w/3} m0,${(gGui.efs.h)/3} h${-(gGui.efs.w)/3} v${(gGui.efs.h)/3} m${-(gGui.efs.w)/3},0 v${-(gGui.efs.h)/3} h${-(gGui.efs.w)/3}' fill='none' stroke-width='1' stroke='${guicol}' stroke-opacity='${buttonOpacity}'/>

        <rect x='${gGui.stop.x}' y='${gGui.stop.y}' width='${gGui.stop.w}' height='${gGui.stop.h}' fill='${guicol}' stroke-width='0' stroke='none' fill-opacity='${buttonOpacity}'/>
        <polygon fill-rule='nonzero' stroke='none' fill='${guicol}' fill-opacity='${buttonOpacity}' points='${gGui.rev.x+gGui.rev.w*(1-(3^0.5)/4)},${gGui.rev.y+gGui.rev.h/2} ${gGui.rev.x+gGui.rev.w},${gGui.rev.y+gGui.rev.h} ${gGui.rev.x+gGui.rev.w},${gGui.play.y}'/>
        <rect x='${gGui.pause.x}' y='${gGui.pause.y}' width='2px' height='${gGui.pause.h}' fill='${guicol}' stroke-width='0' stroke='none'  fill-opacity='${buttonOpacity}'/>
        <rect x='${gGui.pause.x+4}' y='${gGui.pause.y}' width='2px' height='${gGui.pause.h}' fill='${guicol}' stroke-width='0' stroke='none' fill-opacity='${buttonOpacity}'/>
        <polygon fill-rule='nonzero' stroke='none' fill='${guicol}' fill-opacity='${buttonOpacity}' points='${gGui.play.x},${gGui.play.y} ${gGui.play.x},${gGui.play.y+gGui.play.h} ${gGui.play.x+gGui.play.w*(3^0.5)/4},${gGui.play.y+gGui.play.h/2}'/>
 <!--       <text x='${gGui.spx2.x}' y='${gGui.spx2.y+gGui.spx2.h}' text-anchor='start' alignement-baseline='baseline' fill='${guicol}' fill-opacity='${buttonOpacity}' font-size="5px">x2</text> -->
    </svg>`;

        const GUIimg = new Image();
        GUIimg.crossOrigin = "anonymous";

        GUIimg.onerror = function(){trace("failed to load GUIimg",0)};
        GUIimg.onload = function(){
            trace("load GUIimg !!!!!!!!!!!!!!",0);
            events.clear(); // clear canvas 2D
            ctx2d.drawImage(this,0,0); // draw Gui img on canvas 2D
        };
        GUIimg.src = GUIsvg;
        return GUIimg;
    }
    
    gl.guiImg = getGuiImg();
    gl.canvasWidth = canvas.width;
    gl.canvasHeigth = canvas.height;

    function fullscreenchanged(event) {
      // document.fullscreenElement will point to the element that
      // is in fullscreen mode if there is one. If not, the value
      // of the property is null.
      if (document.fullscreenElement) {
        canvas.width = canvas.parentElement.clientWidth;
        canvas.height = canvas.parentElement.clientHeight;
        gl.fullscreen = true;
        events.fullscreen = true;
        onResize();
      } else {
        canvas.height = gl.canvasHeigth;
        canvas.width = gl.canvasWidth;
        gl.fullscreen = false;
        events.fullscreen = false;
        onResize();
        console.log('Leaving fullscreen mode.');
      }
    };

    canvas.parentElement.addEventListener('fullscreenchange', fullscreenchanged);

    var canvasDiv = canvas.parentElement;
 
    function openFullscreen() {
      if (canvasDiv.requestFullscreen) {
        return canvasDiv.requestFullscreen();
      } else if (canvasDiv.webkitRequestFullscreen) { /* Safari */
        return canvasDiv.webkitRequestFullscreen();
      } else if (canvasDiv.msRequestFullscreen) { /* IE11 */
        return canvasDiv.msRequestFullscreen();
      }
    }

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
     /*       canvasDiv.requestFullscreen().catch((err) => {
                alert(`Error attempting to enable fullscreen mode: ${err.message} (${err.name})`);
            }); */
            openFullscreen().catch((err) => {
                alert(`Error attempting to enable fullscreen mode: ${err.message} (${err.name})`);
            }); 
        } else {
            document.exitFullscreen();
        }
    }
    function existCanvasFullscreen(){
            document.exitFullscreen();
    }
 //   var ctx = WebGLDebugUtils.glEnumToString(gl.getError());
/*    WebGLDebugUtils.init(gl);
    // Create debug context that will throw error on invalid WebGL operation.
    
    function throwOnGLError(err, funcName, args) {
       throw WebGLDebugUtils.glEnumToString(err) 
       + "was caused by call to " 
       + funcName;
    };
     
    gl = WebGLDebugUtils.makeDebugContext(gl, throwOnGLError)
*/
    const mat4 = glMatrix.mat4;

    // Continuer seulement si WebGL est disponible et fonctionnel
    if (!gl) {
        alert("Impossible d'initialiser WebGL. Votre navigateur ou votre machine peut ne pas le supporter.");
        return;
    }

  // Définir la couleur d'effacement comme étant le noir, complètement opaque
//  gl.clearColor(1, 1, 1, 0.0);
    gl.clearColor(0.1, 0.1, 0.1, 1.0);
    
 //   gl.disable(gl.DEPTH_TEST);
  //  gl.blendFunc(gl.SRC_ALPHA, gl.ONE_MINUS_SRC_ALPHA);
//    gl.enable(gl.DEPTH_TEST);
 //   gl.depthFunc(gl.ALWAYS);
 
  // Effacer le tampon de couleur avec la couleur d'effacement spécifiée
    if (preserveBuff !== false) {
      gl.clear(gl.DEPTH_BUFFER_BIT);
      } else { gl.clear(gl.COLOR_BUFFER_BIT|gl.DEPTH_BUFFER_BIT); }

// Programme shader de sommet

// version sans VertexColor
/* const vsSource = `
//  attribute vec4 aVertexPosition;
  attribute vec3 aVertexPosition;

  uniform mat4 uModelViewMatrix;
  uniform mat4 uProjectionMatrix;

  void main() {
    gl_Position = uProjectionMatrix * uModelViewMatrix * vec4(aVertexPosition, 1.0);
//    gl_Position = uProjectionMatrix * uModelViewMatrix * aVertexPosition;
  }
`;

// version avec le VertexColor
const vsSource = `
  attribute vec4 aVertexPosition;
  attribute vec4 aVertexColor;

  uniform mat4 uModelViewMatrix;
  uniform mat4 uProjectionMatrix;

  varying lowp vec4 vColor;

  void main(void) {
    gl_Position = uProjectionMatrix * uModelViewMatrix * aVertexPosition;
    vColor = aVertexColor;
  }
`; 
// version avec les textures
const vsSource = `
 //   attribute vec4 aVertexPosition;
    attribute vec3 aVertexPosition;
    attribute vec2 aTextureCoord;

    uniform mat4 uModelViewMatrix;
    uniform mat4 uProjectionMatrix;

    varying highp vec2 vTextureCoord;

    void main(void) {
     gl_Position = uProjectionMatrix * uModelViewMatrix * vec4(aVertexPosition, 1.0);
//     gl_Position = uProjectionMatrix * uModelViewMatrix * aVertexPosition;
      vTextureCoord = aTextureCoord;
    }
  `;
*/
    // version avec texture et lumiere directionnelle
    const vsSource = `
        attribute vec3 aVertexPosition;
        attribute vec3 aVertexNormal;
        attribute vec2 aTextureCoord;

        uniform mat4 uNormalMatrix;
        uniform mat4 uModelViewMatrix;
        uniform mat4 uProjectionMatrix;

        varying highp vec2 vTextureCoord;
        varying highp vec3 vLighting;

        void main(void) {
          gl_Position = uProjectionMatrix * uModelViewMatrix * vec4(aVertexPosition,1.0);
          vTextureCoord = aTextureCoord;

          // Apply lighting effect

          highp vec3 ambientLight = vec3(0.7, 0.7, 0.7);
          highp vec3 directionalLightColor = vec3(0.5, 0.5, 0.5);
          highp vec3 directionalVector = normalize(vec3(0.4, 0.8, 0.85));

          highp vec4 transformedNormal = uNormalMatrix * vec4(aVertexNormal, 1.0);

          highp float directional = min(1.0,max(dot(transformedNormal.xyz, directionalVector), 0.0));
          vLighting = ambientLight + (directionalLightColor * directional);
        }
      `;

// Programme shader de fragment
// version couleur fixée
/*
const fsSource = `
  void main() {
    gl_FragColor = vec4(1.0, 1.0, 1.0, 1.0);
  }
`;

// version couleur interpolée
const fsSource = `
  varying lowp vec4 vColor;

  void main(void) {
    gl_FragColor = vColor;
  }
`; 

// version pour textures
const fsSource = `
    precision mediump int;
    precision mediump float;
    
    varying highp vec2 vTextureCoord;

    uniform sampler2D uSampler;

    void main(void) {
      gl_FragColor = texture2D(uSampler, vTextureCoord);
    }
  `;
*/
    //version pour texture et eclairage directionnel
    const fsSource = `
        varying highp vec2 vTextureCoord;
        varying highp vec3 vLighting;

        uniform sampler2D uSampler;

        void main(void) {
          highp vec4 texelColor = texture2D(uSampler, vTextureCoord);

          gl_FragColor = vec4(texelColor.rgb * vLighting, texelColor.a);
        if(gl_FragColor.a < 0.5){
           discard;
        }
        }
      `;
//
    // Initialiser un programme shader, de façon à ce que WebGL sache comment dessiner nos données
    //
    function initShaderProgram(gl, vsSource, fsSource) {
      const vertexShader = loadShader(gl, gl.VERTEX_SHADER, vsSource);
      const fragmentShader = loadShader(gl, gl.FRAGMENT_SHADER, fsSource);

      // Créer le programme shader

      const shaderProgram = gl.createProgram();
      gl.attachShader(shaderProgram, vertexShader);
      gl.attachShader(shaderProgram, fragmentShader);
      gl.linkProgram(shaderProgram);

      // Si la création du programme shader a échoué, alerte

      if (!gl.getProgramParameter(shaderProgram, gl.LINK_STATUS)) {
        alert('Impossible d\'initialiser le programme shader : ' + gl.getProgramInfoLog(shaderProgram));
        return null;
      }

      return shaderProgram;
    }

    //
    // Crée un shader du type fourni, charge le source et le compile.
    //
    function loadShader(gl, type, source) {
      const shader = gl.createShader(type);

      // Envoyer le source à l'objet shader

      gl.shaderSource(shader, source);

      // Compiler le programme shader

      gl.compileShader(shader);

      // Vérifier s'il a ét compilé avec succès

      if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
        alert('An error occurred compiling the shaders: ' + gl.getShaderInfoLog(shader));
        gl.deleteShader(shader);
        return null;
      }

      return shader;
    }
    trace("before init shadder");
    // exemple pour utiliser le code precedent 
    const shaderProgram = initShaderProgram(gl, vsSource, fsSource);
/*
// version avec color
const programInfo = {
  program: shaderProgram,
  attribLocations: {
    vertexPosition: gl.getAttribLocation(shaderProgram, 'aVertexPosition'),
    vertexColor: gl.getAttribLocation(shaderProgram, 'aVertexColor'), // version avec VertexColor
  },
  uniformLocations: {
    projectionMatrix: gl.getUniformLocation(shaderProgram, 'uProjectionMatrix'),
    modelViewMatrix: gl.getUniformLocation(shaderProgram, 'uModelViewMatrix'),
  },
}; 

// version avec textures
const programInfo = {
  program: shaderProgram,
  attribLocations: {
    vertexPosition: gl.getAttribLocation(shaderProgram, 'aVertexPosition'),
    textureCoord: gl.getAttribLocation(shaderProgram, 'aTextureCoord'),
  },
  uniformLocations: {
    projectionMatrix: gl.getUniformLocation(shaderProgram, 'uProjectionMatrix'),
    modelViewMatrix: gl.getUniformLocation(shaderProgram, 'uModelViewMatrix'),
    uSampler: gl.getUniformLocation(shaderProgram, 'uSampler'),
  },
}; */

    // version avec textures et eclairage directinnel
    const programInfo = {
        program: shaderProgram,
        attribLocations: {
          vertexPosition: gl.getAttribLocation(shaderProgram, 'aVertexPosition'),
          vertexNormal: gl.getAttribLocation(shaderProgram, 'aVertexNormal'),
          textureCoord: gl.getAttribLocation(shaderProgram, 'aTextureCoord'),
        },
        uniformLocations: {
          projectionMatrix: gl.getUniformLocation(shaderProgram, 'uProjectionMatrix'),
          modelViewMatrix: gl.getUniformLocation(shaderProgram, 'uModelViewMatrix'),
          normalMatrix: gl.getUniformLocation(shaderProgram, 'uNormalMatrix'),
          uSampler: gl.getUniformLocation(shaderProgram, 'uSampler'),
        },
      };


    var squareRotation = 0.0;

    function onDeltaMouseInitGL(gl) {
        if (gl.mdX != 0 || gl.mdY != 0 || gl.mdZ != 0 || 
            gl.zplus == true || gl.zminus == true || 
            gl.deltaScrollY != 0 || gl.deltaScrollX != 0 || 
            gl.mdX2 != 0 || gl.mdY2 != 0)
            initGl(gl);
        // body...
    }

    function initGl(gl) {
       gl.viewport(0, 0, canvas.width, canvas.height);

       if (gl.rotX == null) gl.rotX = Math.PI / 4;
       if (gl.rotY == null) gl.rotY = 0;
       if (gl.rotZ == null) gl.rotZ = 0;

       if (gl.camZ == null) gl.camZ = -5.2;
       if (gl.camX == null) gl.camX = 0;
       if (gl.camY == null) gl.camY = 0;

       if (gl.mdY != 0) gl.rotY = gl.rotY + gl.mdX * 5;
       if (gl.mdX != 0) gl.rotX = gl.rotX + gl.mdY * 5;
       if (gl.mdZ != 0) gl.rotZ = gl.rotZ + gl.mdZ * 5;

       if (gl.mdX2 != 0 || gl.mdY2 != 0) {
            gl.camX += gl.mdX2 * Math.abs(gl.camZ);
            gl.camY -= gl.mdY2 * Math.abs(gl.camZ);
       }

       if (gl.zplus) {gl.camZ += 0.5; gl.zplus = false;}
       else if (gl.zminus) {gl.camZ -= 0.5; gl.zminus = false;}
       else if (gl.deltaScrollY != 0) {gl.camZ += gl.deltaScrollY * Math.exp(Math.abs(gl.deltaScrollY*3)); gl.deltaScrollY=0;}

        // Créer une matrice de perspective, une matrice spéciale qui est utilisée pour
      // simuler la distorsion de la perspective dans une caméra.
      // Notre champ de vision est de 45 degrés, avec un rapport largeur/hauteur qui
      // correspond à la taille d'affichage du canvas ;
      // et nous voulons seulement voir les objets situés entre 0,1 unité et 100 unités
      // à partir de la caméra.

      const fieldOfView = 45 * Math.PI / 180;   // en radians
      const aspect = gl.canvas.clientWidth / gl.canvas.clientHeight;
      const zNear = 0.1;
      const zFar = 100.0;
      const projectionMatrix = mat4.create();

      // note: glmatrix.js a toujours comme premier argument la destination
      // où stocker le résultat.
      mat4.perspective(projectionMatrix,
                        fieldOfView,
                        aspect,
                        zNear,
                        zFar);

      // Définir la position de dessin comme étant le point "origine", qui est
      // le centre de la scène.
      const modelViewMatrix = mat4.create();

      // Commencer maintenant à déplacer la position de dessin un peu vers là où
      // nous voulons commencer à dessiner le carré.
    /*
      mat4.translate(modelViewMatrix,     // matrice de destination
                      modelViewMatrix,     // matrice de déplacement
                      translateVector);
                    //  [0.1, -0.05, -5.2]);  // quantité de déplacement

      

      mat4.scale(modelViewMatrix,     // matrice de destination
                      modelViewMatrix,     // matrice de déplacement
                      scaleVector);  // vecteur de scale
    */  
              
        mat4.translate(modelViewMatrix,     // matrice de destination
                      modelViewMatrix,     // matrice de déplacement
                    //  translateVector);
                      [gl.camX, gl.camY, gl.camZ]);  // quantité de déplacement


      // faire tourner le carré
      mat4.rotate(modelViewMatrix,  // matrice de destination
                modelViewMatrix,  // matrice de rotat ion
//                Math.PI / 4,//  squareRotation * 0.1,   // rotation en radians
                gl.rotX,
                [1, 0, 0]);       // axe autour duquel tourner


      // faire tourner le carré
      mat4.rotate(modelViewMatrix,  // matrice de destination
                modelViewMatrix,  // matrice de rotat ion
//                Math.PI / 4,//  squareRotation * 0.1,   // rotation en radians
                gl.rotY,
                [0, 1, 0]);       // axe autour duquel tourner

     // faire tourner le carré
      mat4.rotate(modelViewMatrix,  // matrice de destination
                modelViewMatrix,  // matrice de rotat ion
//                Math.PI / 4,//  squareRotation * 0.1,   // rotation en radians
                gl.rotZ,
                [0, 0, 1]);       // axe autour duquel tourner

    /*
    // faire tourner le carré
      mat4.rotate(modelViewMatrix,  // matrice de destination
                modelViewMatrix,  // matrice de rotat ion
                squareRotation * 0.1,   // rotation en radians
                [0.0, 1.0, 0.0]);       // axe autour duquel tourner

    */


      // Indiquer à WebGL d'utiliser notre programme pour dessiner

      gl.useProgram(programInfo.program);

      // Définir les uniformes du shader

      gl.uniformMatrix4fv(
          programInfo.uniformLocations.projectionMatrix,
          false,
          projectionMatrix);

      gl.uniformMatrix4fv(
          programInfo.uniformLocations.modelViewMatrix,
          false,
          modelViewMatrix);

      // normalMatrix
      const normalMatrix = mat4.create();
      mat4.invert(normalMatrix, modelViewMatrix);
      mat4.transpose(normalMatrix, normalMatrix);

      // …
      gl.uniformMatrix4fv(
          programInfo.uniformLocations.normalMatrix,
          false,
          normalMatrix);
    }

    // Rendu de la scene
    function drawScene(gl, programInfo, buffers, deltaTime,textures_obj) {
      
        onDeltaMouseInitGL(gl);
      
      var transparency = 1.0;
      if (alpha_channel_active) transparency = 0.0;
    //  gl.clearColor(1.0, 1.0, 1.0, transparency);  // effacement en noir, complètement opaque
      gl.clearColor(0.1, 0.1, 0.1, 1.0);  // effacement en noir, complètement opaque
     // gl.clearDepth(1.0);                 // tout effacer

     // gl.disable(gl.DEPTH_TEST);           // activer le test de profondeur
      gl.enable(gl.DEPTH_TEST);           // activer le test de profondeur
      gl.depthFunc(gl.LEQUAL); // LEQUAL);            // les choses proches cachent les choses lointaines
    // gl.blendFunc(gl.SRC_ALPHA, gl.ONE_MINUS_SRC_ALPHA);
      // Effacer le canevas avant que nous ne commencions à dessiner dessus.

        if (preserveBuff) {
              gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
          } 
        else {gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);}


        // Indiquer à WebGL comment extraire les coordonnées de texture du tampon
        {
          const num = 2; // chaque coordonnée est composée de 2 valeurs
          const type = gl.FLOAT; // les données dans le tampon sont des flottants 32 bits
          const normalize = false; // ne pas normaliser
          const stride = 0; // combien d'octets à récupérer entre un jeu et le suivant
          const offset = 0; // à combien d'octets du début faut-il commencer
          gl.bindBuffer(gl.ARRAY_BUFFER, buffers.textureCoords);
        //  trace(programInfo);
          const texCoord = programInfo.attribLocations.textureCoord;
        //  trace(texCoord);
          gl.vertexAttribPointer(texCoord, num, type, normalize, stride, offset);
          gl.enableVertexAttribArray(texCoord);
        }

      // Indiquer à WebGL comment extraire les positions à partir du tampon des
      // positions pour les mettre dans l'attribut vertexPosition.
      {
       // const numComponents = 2;  // extraire 2 valeurs par itération pour 2D
        const numComponents = 3;  // extraire 3 valeurs par itération pour 3D
        const type = gl.FLOAT;    // les données dans le tampon sont des flottants 32bit
        const normalize = false;  // ne pas normaliser
        const stride = 0;         // combien d'octets à extraire entre un jeu de valeurs et le suivant
                                  // 0 = utiliser le type et numComponents ci-dessus
        const offset = 0;         // démarrer à partir de combien d'octets dans le tampon

    // Indiquer à WebGL quelles positions utiliser pour les points de sommet
        gl.bindBuffer(gl.ARRAY_BUFFER, buffers.position); 
    // Indiquer à WebGL quels indices utiliser pour indexer les sommets
        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, buffers.indices);  // 3D
        
        gl.vertexAttribPointer(
            programInfo.attribLocations.vertexPosition,
            numComponents,
            type,
            normalize,
            stride,
            offset);
        gl.enableVertexAttribArray(programInfo.attribLocations.vertexPosition);
      }

    // Tell WebGL how to pull out the normals from
      // the normal buffer into the vertexNormal attribute.
      {
        const numComponents = 3;
        const type = gl.FLOAT;
        const normalize = false;
        const stride = 0;
        const offset = 0;
        gl.bindBuffer(gl.ARRAY_BUFFER, buffers.normal);
        gl.vertexAttribPointer(
            programInfo.attribLocations.vertexNormal,
            numComponents,
            type,
            normalize,
            stride,
            offset);
        gl.enableVertexAttribArray(programInfo.attribLocations.vertexNormal);
      }

      {
        var ooffset = 0;
    //    const vertexCount = 4; // nbr de somments si drawArray
    //    const vertexCount = 6;  // 3 sommets / triangle sir drawElements
    //  gl.drawArrays(gl.TRIANGLE_STRIP, offset, vertexCount); // carré 2D
        const nbBytes = 2;

        for(var i=0;i<structures_objects.length;i++) {
            const obj = structures_objects[i];
    //        const loffset = drawGroup(gl,programInfo,obj,textures_obj,ooffset); // obj.vertexCount * 2;
            const loffset = drawLayoutAtlas(gl,programInfo,obj,textures_obj,ooffset); // obj.vertexCount * 2;
            trace("loffset:" + loffset);        
            ooffset += loffset;
            trace("ooffset:" + ooffset);
        }
      }


      squareRotation += deltaTime;
    }


    function initSetup(grp) {
        trace("************ initSetup : " + grp.tag);
        grp.setup = 0;
        if (Array.isArray(grp.things)) {
            grp.things.forEach(function(obj){initSetup(obj)});
        }
    }

    var then = 0;
    var elapstime = 0;
    var timeInSeq = 0;
    var indexSeq = 0;
    var maxIndexSeq = (sequences.length -1);
    var FPS = 60; // Frame Per Second
    var stop = false;
    var savetimer = 0;
    const nbSecondsBetweenSave = 1;

    // const textures = loadAllTexture(gl);
    var textures_obj = initTextures(gl, structures_objects, textures_url, textures_option);

    // Dessiner la scène répétitivement
    function render(now) {
        now *= 0.001;  // conversion en secondes
        // firstStat = gl.startplay;
        var _saveState = gl.forceSaveState;
        if (then == 0) {then = Math.max(0,now - 2/FPS);}
        if (gl.startplay) {gl.startplay = false; then = now;}
        var deltaTime = now - then;
        if (stop) deltaTime = 0;
        const curSeq = sequences[Math.min(maxIndexSeq,indexSeq)];
        const durationPlusPause = (curSeq.duration + curSeq.pause + 1/FPS);
        if (!stop && !(gl.renderStop) && deltaTime * FPS < 1)  {requestAnimationFrame(render);return;}
        
        then = now;
        elapstime = elapstime + deltaTime;
        // trace("deltaTime:".now);
        timeInSeq = timeInSeq + deltaTime;
        
        savetimer += deltaTime;
        if (savetimer >= nbSecondsBetweenSave) {savetimer = 0; saveState();}
        
        trace("***************** scene_index:" + scene_index + " nb_scenes:" + scenes_arrays.nb_scenes + " then:" + then + " indexSeq:" + indexSeq + " timeInSeq:" + timeInSeq + " dur+pause:" + durationPlusPause + "***********************",10);
        
        if (gl.renderPause || gl.renderStop) {
            if (gl.renderStop) {
                trace("render stop => Stop rendering & MAJ scene_index, indexSeq, timeInSeq !!!!",0);
                scene_index = gl.currentSceneIndex;
                indexSeq = gl.currentSequenceIndex;
                timeInSeq = gl.currentTimeInSequence;
                stop = false;
                structures_objects = scenes_arrays.scene_things[scene_index];
                textures_url = scenes_arrays.scene_textures_url[scene_index];
                textures_option = scenes_arrays.scene_textures_options[scene_index];
                sequences = scenes_arrays.scene_sequences[scene_index];
              //  textures_obj = initTextures(gl, structures_objects, textures_url, textures_option);
                maxIndexSeq = (sequences.length -1);
                structures_objects.forEach(function(struct_obj){initSetup(struct_obj)});
                renderering = false; 
            } else {trace("render pause : stop rendering",0); renderering = false;}
        }
        else if (!stop && indexSeq == maxIndexSeq && timeInSeq > durationPlusPause && scene_index + 1 < scenes_arrays.nb_scenes) {
                trace("next scene >>>>>>>>>",0);
                scene_index += 1;
                indexSeq = 0;
                timeInSeq = 0;
                
                structures_objects = scenes_arrays.scene_things[scene_index];
                textures_url = scenes_arrays.scene_textures_url[scene_index];
                textures_option = scenes_arrays.scene_textures_options[scene_index];
                sequences = scenes_arrays.scene_sequences[scene_index];
                textures_obj = initTextures(gl, structures_objects, textures_url, textures_option);
                maxIndexSeq = (sequences.length -1);
                requestAnimationFrame(render);
        }
        else if (!stop && indexSeq >= maxIndexSeq && timeInSeq > durationPlusPause && scene_index + 1 >= scenes_arrays.nb_scenes) {
                trace("all scene finished -> stop !!!!!!!!!",0);
                gl.lastDeltaTime = 0;
                gl.endBuffers = initBuffers(gl,structures_objects,sequences,indexSeq,timeInSeq,now);
                renderering = false;
              //  gl.comment = "End of animation";
                stop = true;
                renderering = false;
        }
        else if (!stop) {
              if (indexSeq < sequences.length && timeInSeq > durationPlusPause) {
                trace("end sequence !",0);
                deltaTime = durationPlusPause - timeInSeq;
                timeInSeq = durationPlusPause;
                structures_objects.forEach(function(struct_obj){initSetup(struct_obj)});

                gl.endBuffers = initBuffers(gl,structures_objects,sequences,indexSeq,timeInSeq,now);                
                gl.lastDeltaTime = deltaTime;
                drawScene(gl, programInfo, gl.endBuffers, deltaTime,textures_obj);
                indexSeq += 1;
                timeInSeq = 0;
              } 
              else if (indexSeq < sequences.length) {
                trace("follow sequence ...");
                gl.endBuffers = initBuffers(gl,structures_objects,sequences,indexSeq,timeInSeq,now);  
                if (_saveState) {
                    gl.currentSceneIndex = scene_index;
                    gl.currentSequenceIndex = indexSeq;
                    gl.currentTimeInSequence = timeInSeq;                    
                    saveState(); 
                    _saveState = false;}            
                gl.lastDeltaTime = deltaTime;
                drawScene(gl, programInfo, gl.endBuffers, deltaTime,textures_obj);
              }

        //      drawScene(gl, programInfo, initBuffers(gl,elapstime), deltaTime);
              requestAnimationFrame(render);
        }
    }
    initGl(gl);
    gl.renderCallback = render;
    if (renderering == false) {renderering = true; requestAnimationFrame(render);}



    canvas2D.addEventListener("mouseout", function(){
        events.clear();
        gl.mDown = -1;
        gl.mdX = 0;
        gl.mdY = 0;
        gl.mdZ = 0;
        gl.mdX2 = 0;
        gl.mdY2 = 0;
      //  writeMessage(ctx2d, "Mouseover me!");
        
    }, false);

    canvas2D.addEventListener("mousedown", function(evt){
        events.clear();
        evt.preventDefault();
        evt.stopPropagation();
        trace("mousedown",0);
        var mousePos = events.getMousePos();
        if (evt.button == 0 && !evt.shiftKey && !evt.ctrlKey) {
            gl.mDown = 0;
            gl.oldMouseX = mousePos.x;
            gl.oldMouseY = mousePos.y;
            writeMessage(ctx2d, "Mouse Down !");
            checkClickOnGui(mousePos.x,mousePos.y);
        } else if (evt.button == 0 && evt.shiftKey) {
            gl.mDown = 1;
            gl.oldMouseX = mousePos.x;
            gl.oldMouseY = mousePos.y;
        } else if (evt.button == 0 && evt.ctrlKey) {
            gl.mDown = 2;
            gl.oldMouseX = mousePos.x;
            gl.oldMouseY = mousePos.y;
        }

    }, false);
    
    canvas2D.addEventListener("mouseup", function(){
        events.clear();
        gl.mDown = -1;
        gl.mdX = 0;
        gl.mdY = 0;
        gl.mdZ = 0;
        gl.mdX2 = 0;
        gl.mdY2 = 0;
        writeMessage(ctx2d, "Mouse Up !");
    }, false);
    
    function redrawScene() {
        if (gl.endBuffers != null) {
            if (gl.lastDeltaTime == null) gl.lastDeltaTime = 0;
            drawScene(gl, programInfo,gl.endBuffers,gl.lastTimeInSeq,textures_obj);
        }
    }
    gl.deltaScrollY = 0;

    canvas2D.addEventListener("mousemove", function(evt){
         var mousePos = events.getMousePos();
        events.clear();

        if (mousePos !== null) {
            message = "Mouse position: " + mousePos.x + "," + mousePos.y;
            writeMessage(ctx2d, message);
        }
        if (gl.mDown == 0) {
            trace("mouseMove0, endBuffers: " + gl.endBuffers,3);
            setDeltaMousePos(mousePos.x,mousePos.y);
            redrawScene();
            gl.oldMouseX = mousePos.x;
            gl.oldMouseY = mousePos.y;
        } else if (gl.mDown == 1) {
            trace("mouseMove1",3);
            setDeltaMousePosRight(mousePos.x,mousePos.y);
            redrawScene();
            gl.oldMouseX = mousePos.x;
            gl.oldMouseY = mousePos.y;
        } else if (gl.mDown == 2) {
            trace("mouseMove2",3);
            setDeltaMousePosCtrl(mousePos.x,mousePos.y);
            redrawScene();
            gl.oldMouseX = mousePos.x;
            gl.oldMouseY = mousePos.y;
        }

    }, false);

    canvas2D.addEventListener("wheel", evt => {
        trace("wheel",3);
        events.clear();
        evt.preventDefault();
        evt.stopPropagation();
        var mousePos = events.getMousePos();
        if (mousePos !== null) {
            message = "Mouse position: " + mousePos.x + "," + mousePos.y;
            writeMessage(ctx2d, message);
        }
        setDeltaScroll2(evt.deltaX,evt.deltaY);
        redrawScene();
    }, false);

    gl.firstMove = true;
    // mobile events
    canvas2D.addEventListener("touchstart", evt => {
        events.clear();
        var mousePos = events.getTouchPos();
        gl.mDown = 0;
        gl.firstMove = true;
        gl.oldMouseX = mousePos.x;
        gl.oldMouseY = mousePos.y;
        writeMessage(ctx2d, "Touch Start !");
        checkClickOnGui(mousePos.x,mousePos.y);
        }, false);
        
    canvas2D.addEventListener("touchmove", evt => {
        trace("touchMove",0);
        events.clear();
        var mousePos = events.getTouchPos();
        if (mousePos !== null) {
          //  message = "Mouse position: " + mousePos.x + "," + mousePos.y;
          //  writeMessage(ctx2d, message);
        }
        if (gl.firstMove) {
            gl.firstMove = false;
            gl.oldMouseX = mousePos.x;
            gl.oldMouseY = mousePos.y;
        } else if (evt.touches.length == 1) {
            setDeltaMousePos(mousePos.x,mousePos.y);
            redrawScene();
            gl.oldMouseX = mousePos.x;
            gl.oldMouseY = mousePos.y;
        } /* else if (evt.touches.length == 2) {
      //      const touch = evt.changedTouches.item(1);
       //     message = "rotation: " + touch.rotationAngle; // + ", scale: " + touch.scale;
         //   writeMessage(ctx2d, message);
        
     //      message = "Mouse position: " + parseInt(mousePos.x) + ", " + parseInt(mousePos.y) + ", X2,y2:" + parseInt(mousePos.x2) + ", " + parseInt(mousePos.y2); 
      //     writeMessage(ctx2d, message);

            setDeltaScroll(mousePos.x,mousePos.y,mousePos.x2,mousePos.y2);
            redrawScene();
        //    gl.oldMouseX = mousePos.x;
        //    gl.oldMouseY = mousePos.y;
        } */
    }, false);
        
    canvas2D.addEventListener("touchend", evt => {
        events.clear();
        gl.mDown = -1;
        gl.mdX = 0;
        gl.mdY = 0;
        gl.mdZ = 0;
        writeMessage(ctx2d, "Touch End !");
        events.drawStage();
        }, false);
    
    events.setDrawStage(function(){
    //    gl.guiImg = getGuiImg();
        gl.ctx2D.drawImage(gl.guiImg,0,0);
        // writeComment(gl,gl.comment);
    });
    onResize();
}
