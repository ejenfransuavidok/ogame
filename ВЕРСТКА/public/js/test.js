// Scene, Camera, Renderer
let renderer = new THREE.WebGLRenderer();
let scene = new THREE.Scene();
let aspect = window.innerWidth / window.innerHeight;
let camera = new THREE.PerspectiveCamera(45, aspect, 0.1, 1500);
let cameraRotation = 0;
let cameraRotationSpeed = 0.001;
let cameraAutoRotation = true;
let orbitControls = new THREE.OrbitControls(camera);

// Lights
let spotLight = new THREE.SpotLight(0xffffff, 1, 0, 10, 2);

// Texture Loader
let textureLoader = new THREE.TextureLoader();

// Planet Proto
let planetProto = {

	texture: function(material, property, uri) {
		let textureLoader = new THREE.TextureLoader();
		textureLoader.crossOrigin = true;
		textureLoader.load(
			uri,
			function(texture) {
				material[property] = texture;
				material.needsUpdate = true;
			}
		);
	}
};

let createPlanet = function(options) {
	// Create the planet's Surface
	let surfaceGeometry = new THREE.SphereGeometry(options.surface.size, 32, 32);
	let surfaceMaterial = new THREE.MeshPhongMaterial({
		bumpScale: 0.05,
		specular: new THREE.Color('grey'),
		shininess: 10,

		map: new THREE.TextureLoader({crossOrigin: true}).load('https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthmap1k.jpg'),
		bumpMap: new THREE.TextureLoader({crossOrigin: true}).load('https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthbump1k.jpg'),
		specularMap: new THREE.TextureLoader({crossOrigin: true}).load('https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthspec1k.jpg')

	});
	let surface = new THREE.Mesh(surfaceGeometry, surfaceMaterial);

	// Create the planet's Atmosphere
	let atmosphereGeometry = new THREE.SphereGeometry(options.surface.size + options.atmosphere.size, 32, 32);
	let atmosphereMaterial = new THREE.MeshPhongMaterial({
		side: THREE.DoubleSide,
		transparent: true,
		opacity: 0.8,

		map: new THREE.TextureLoader({crossOrigin: true}).load('https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthcloudmap.jpg'),
		alphaMap: new THREE.TextureLoader({crossOrigin: true}).load('https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthcloudmaptrans.jpg')

	});
	let atmosphere = new THREE.Mesh(atmosphereGeometry, atmosphereMaterial);

	// Create the planet's Atmospheric glow
	let atmosphericGlowGeometry = new THREE.SphereGeometry(options.surface.size + options.atmosphere.size + options.atmosphere.glow.size, 32, 32);
	let atmosphericGlowMaterial = new THREE.ShaderMaterial({
		uniforms: {
			'c': {type: 'f',value: 0.7},
			'p': {type: 'f',value: 7},
			glowColor: {type: 'c',value: new THREE.Color(0x93cfef)},
			viewVector: {type: 'v3',value: camera.position}
		},
		vertexShader: 'uniform vec3 viewVector;uniform float c;uniform float p;varying float intensity;void main() {vec3 vNormal = normalize( normalMatrix * normal );vec3 vNormel = normalize( normalMatrix * viewVector );intensity = pow( c - dot(vNormal, vNormel), p );gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );}',
		fragmentShader: 'uniform vec3 glowColor;varying float intensity;void main(){vec3 glow = glowColor * intensity;gl_FragColor = vec4( glow, 1.0 );}',
		side: THREE.BackSide,
		blending: THREE.AdditiveBlending,
		transparent: true
	});

	let atmosphericGlow = new THREE.Mesh(atmosphericGlowGeometry, atmosphericGlowMaterial);

	// Nest the planet's Surface and Atmosphere into a planet object
	let planet = new THREE.Object3D();
	surface.name = 'surface';
	atmosphere.name = 'atmosphere';
	atmosphericGlow.name = 'atmosphericGlow';
	planet.add(surface);
	planet.add(atmosphere);
	planet.add(atmosphericGlow);

	// Load the Surface's textures


	planetProto.texture(surfaceMaterial, 'map', 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthmap1k.jpg');
	planetProto.texture(surfaceMaterial, 'bumpMap', 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthbump1k.jpg');
	planetProto.texture(surfaceMaterial, 'specularMap', 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthspec1k.jpg');


	let textureLoader = new THREE.TextureLoader();
	textureLoader.crossOrigin = true;
	textureLoader.load(
		'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthmap1k.jpg',
		function(texture) {
			surfaceMaterial['map'] = texture;
			surfaceMaterial.needsUpdate = true;
		}
	);


	texture = function(material, property, uri) {
		let textureLoader = new THREE.TextureLoader();
		textureLoader.crossOrigin = true;
		textureLoader.load(
			uri,
			function(texture) {
				material[property] = texture;
				material.needsUpdate = true;
			}
		);
	}



	// Load the Atmosphere's texture
	for (let textureProperty in options.atmosphere.textures) {
		planetProto.texture(
			atmosphereMaterial,
			textureProperty,
			options.atmosphere.textures[textureProperty]
		);
	}

	return planet;
};

let earth = createPlanet({
	surface: {
		size: 0.5,
		material: {
			bumpScale: 0.05,
			specular: new THREE.Color('grey'),
			shininess: 10
		},
		textures: {
			map: 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthmap1k.jpg',
			bumpMap: 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthbump1k.jpg',
			specularMap: 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthspec1k.jpg'
		}
	},
	atmosphere: {
		size: 0.003,
		material: {
			opacity: 0.8
		},
		textures: {
			map: 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthcloudmap.jpg',
			alphaMap: 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/earthcloudmaptrans.jpg'
		},
		glow: {
			size: 0.02,
			intensity: 0.7,
			fade: 7,
			color: 0x93cfef
		}
	},
});





// Galaxy
let galaxyGeometry = new THREE.SphereGeometry(100, 32, 32);
let galaxyMaterial = new THREE.MeshBasicMaterial({
	side: THREE.BackSide
});
let galaxy = new THREE.Mesh(galaxyGeometry, galaxyMaterial);

// Load Galaxy Textures
textureLoader.crossOrigin = true;
textureLoader.load(
	'https://s3-us-west-2.amazonaws.com/s.cdpn.io/141228/starfield.png',
	function(texture) {
		galaxyMaterial.map = texture;
		scene.add(galaxy);
	}
);

// Scene, Camera, Renderer Configuration
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);

camera.position.set(1,1,1);
orbitControls.enabled = !cameraAutoRotation;

scene.add(camera);
scene.add(spotLight);
scene.add(earth);

// Light Configurations
spotLight.position.set(2, 0, 1);

// Mesh Configurations
earth.receiveShadow = true;
earth.castShadow = true;
earth.getObjectByName('surface').geometry.center();

// On window resize, adjust camera aspect ratio and renderer size
window.addEventListener('resize', function() {
	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();
	renderer.setSize(window.innerWidth, window.innerHeight);
});

// Main render function
let render = function() {
	earth.getObjectByName('surface').rotation.y += 1/32 * 0.01;
	earth.getObjectByName('atmosphere').rotation.y += 1/16 * 0.01;
	if (cameraAutoRotation) {
		cameraRotation += cameraRotationSpeed;
		camera.position.y = 0;
		camera.position.x = 2 * Math.sin(cameraRotation);
		camera.position.z = 2 * Math.cos(cameraRotation);
		camera.lookAt(earth.position);
	}
	requestAnimationFrame(render);
	renderer.render(scene, camera);
};

render();
