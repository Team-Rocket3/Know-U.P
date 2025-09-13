const citiesData = [
  {
    id: 1,
    name: "Varanasi",
    description: "One of the oldest living cities in the world, famous for its ghats and the sacred Ganges river.",
    category: "religious",
    imageUrl: "cities/Dashawamedha_Ghat_in_Varanasi_2.jpg",
    rate: 1000
  },
  {
    id: 2,
    name: "Agra",
    description: "Home to the magnificent Taj Mahal, a UNESCO World Heritage Site and symbol of love.",
    category: "heritage",
    imageUrl: "cities//Taj_Mahal,_Agra,_India.jpg",
    rate: 1000
  },
  {
    id: 3,
    name: "Prayagraj",
    description: "Known for the sacred Sangam, the confluence of the Ganges, Yamuna, and mythical Saraswati rivers.",
    category: "religious",
    imageUrl: "cities/Indian_kumbh_Festival.jpg",
    rate: 1000
  },
  {
    id: 4,
    name: "Gautam Buddha Nagar (Noida)",
    description: "A modern city near Delhi, known for business hubs, tech parks, and rapid urban development.",
    category: "modern",
    imageUrl: "cities/View_of_Noida_city_from_the_Hilton_Noida.jpg",
    rate: 1000
  },
  {
    id: 5,
    name: "Lucknow",
    description: "Famous for its Nawabi heritage, delicious kebabs, and intricate Mughal architecture.",
    category: "heritage",
    imageUrl: "cities/Rumi_Darwaza_-_The_Breathtaking_Architecture_01.jpg",
    rate: 1000
  },
  {
    id: 6,
    name: "Jhansi",
    description: "Historic city known for Rani Lakshmibai and its forts that played a key role in India's freedom struggle.",
    category: "heritage",
    imageUrl: "cities/Jhansi.jpg",
    rate: 800
  },
  {
    id: 7,
    name: "Aligarh",
    description: "Famous for its Muslim educational institutions and rich cultural heritage.",
    category: "modern",
    imageUrl: "cities/AMU_Masjid_-_panoramio.jpg",
    rate: 800
  },
  {
    id: 8,
    name: "Ambedkar Nagar",
    description: "Known for its connection to Dr. B.R. Ambedkar and its historical significance.",
    category: "religious",
    imageUrl: "cities/Hanswar_koat.jpg",
    rate: 800
  },
  {
    id: 9,
    name: "Amethi",
    description: "Renowned for its political significance and lush agricultural lands.",
    category: "heritage",
    imageUrl: "cities/Kohra_Rajmahal_-_Dec_2022.jpg",
    rate: 800
  },
  {
    id: 10,
    name: "Amroha",
    description: "Famous for its muslins and a mix of cultural traditions.",
    category: "#",
    imageUrl: "cities/Azakhana_wazeer_un_nisa.jpg",
    rate: 800
  },
  {
    id: 11,
    name: "Auraiya",
    description: "Known for its industries and agricultural productivity in Uttar Pradesh.",
    category: "#",
    imageUrl: "cities/Auraiya.jpg",
    rate: 800
  },
  {
    id: 12,
    name: "Azamgarh",
    description: "A city famous for literature and its vibrant folk culture.",
    category: "#",
    imageUrl: "cities/Azamgarh_Railway_Station.jpg",
    rate: 800
  },
  {
    id: 13,
    name: "Baghpat",
    description: "Known for its historical forts and agricultural significance.",
    category: "#",
    imageUrl: "cities/Baghpat.jpg",
    rate: 800
  },
  {
    id: 14,
    name: "Bahraich",
    description: "A famous religious destination with multiple sacred shrines and temples.",
    category: "religious",
    imageUrl: "cities/Bahraich_Clock_Tower.jpg",
    rate: 800
  },
  {
    id: 15,
    name: "Ballia",
    description: "Known for its vibrant festivals and historical role in India's independence movement.",
    category: "heritage",
    imageUrl: "cities/Vrittikut_Ashram_Pakdi_Ballia.jpg",
    rate: 800
  },
  {
    id: 16,
    name: "Balrampur",
    description: "Famous for the Shravasti Buddha pilgrimage site and ancient monasteries.",
    category: "religious",
    imageUrl: "cities/Tulsipur,_Devi_Patan.jpg",
    rate: 800
  },
  {
    id: 17,
    name: "Banda",
    description: "Known for its historical forts and rich natural resources.",
    category: "heritage",
    imageUrl: "cities/Banda_MC,_UP.png",
    rate: 800
  },
  {
    id: 18,
    name: "Barabanki",
    description: "Rich in Awadhi culture and known for its sweet delicacies.",
    category: "religious",
    imageUrl: "cities/K_D_Singh_Babu_Stadium.jpg",
    rate: 800
  },
  {
    id: 19,
    name: "Bareilly",
    description: "Known for its bazaars, handicrafts, and historical monuments.",
    category: "religious",
    imageUrl: "cities/Anand_Ashram.jpg",
    rate: 800
  },
  {
    id: 20,
    name: "Basti",
    description: "A culturally rich city with historical temples and ancient heritage sites.",
    category: "religious",
    imageUrl: "cities/Ahichchhatra_Shwethambar_Tirth_(1).jpg",
    rate: 800
  },
  {
    id: 21,
    name: "Bhadohi",
    description: "Famous internationally for its exquisite carpet weaving industry.",
    category: "#",
    imageUrl: "cities/Morva_River_Front.png",
    rate: 800
  },
  {
    id: 22,
    name: "Bijnor",
    description: "Known for its agricultural markets and religious diversity.",
    category: "#",
    imageUrl: "cities/Bijnor_Railway_Station.jpg",
    rate: 800
  },
  {
    id: 23,
    name: "Budaun",
    description: "A city with historical significance known for ancient architecture and traditions.",
    category: "#",
    imageUrl: "cities/Missions_and_missionary_society_of_the_Methodist_Episcopal_Church_(1895)_(14762905532).jpg",
    rate: 800
  },
  {
    id: 24,
    name: "Bulandshahr",
    description: "A rapidly developing city with educational institutions and industrial areas.",
    category: "modern",
    imageUrl: "cities/Town_Hall_Bulandshahr._North_Verandah._Photograph_by_Chunni_Lál.jpg",
    rate: 800
  },
  {
    id: 25,
    name: "Chandauli",
    description: "Known for its lush green landscapes and agricultural prominence.",
    category: "#",
    imageUrl: "cities/Rajdari_Waterfall_4,_Uttar_pradesh,_India.jpg",
    rate: 800
  },
  {
    id: 26,
    name: "Chitrakoot",
    description: "A sacred town filled with temples, caves, and pilgrimage spots tied to ancient epics.",
    category: "religious",
    imageUrl: "cities/Chitrakoot_bathing_ghats_on_the_Mandakini_River_INDIA.jpg",
    rate: 1000
  },
  {
    id: 27,
    name: "Deoria",
    description: "Known for its natural beauty and cultural heritage festivals.",
    category: "#",
    imageUrl: "cities/ShyamMandirDeoria.jpg",
    rate: 800
  },
  {
    id: 28,
    name: "Etah",
    description: "A historic city with religious sites and vibrant local markets.",
    category: "#",
    imageUrl: "cities/Etah.avif",
    rate: 800
  },
  {
    id: 29,
    name: "Etawah",
    description: "An important agricultural town with a history rooted in the Mughal era.",
    category: "#",
    imageUrl: "cities/Ravines_in_Etawah.jpg",
    rate: 800
  },
  {
    id: 30,
    name: "Ayodhya",
    description: "Birthplace of Lord Ram and a center of spirituality and history.",
    category: "religious",
    imageUrl: "cities/Gulabbari.jpg",
    rate: 1000
  },
  {
    id: 31,
    name: "Farrukhabad",
    description: "Known for its weaving industry and participation in India's freedom movements.",
    category: "#",
    imageUrl: "cities/Tomb_of_Nawab_Rasheed_Khan_Kaimganj.jpg",
    rate: 800
  },
  {
    id: 32,
    name: "Fatehpur",
    description: "Rich in historical fortresses and archaeological sites dating back centuries.",
    category: "heritage",
    imageUrl: "cities/Fatehput_Sikiri_Buland_Darwaza_gate_2010.jpg",
    rate: 800
  },
  {
    id: 33,
    name: "Firozabad",
    description: "Famous as the 'Glass City' of India for its glass bangles and craftsmanship.",
    category: "#",
    imageUrl: "cities/Firozabad_UP_2.jpg",
    rate: 800
  },
  {
    id: 34,
    name: "Unnao",
    description: "Known for its furniture industry and historical temples.",
    category: "#",
    imageUrl: "cities/Nawabganj_Bird_Sanctuary,_Unnao_03.jpg",
    rate: 800
  },
  {
    id: 35,
    name: "Ghaziabad",
    description: "An industrial city adjacent to Delhi, known for rapid urban growth and connectivity.",
    category: "modern",
    imageUrl: "cities/New_Academic_Block_&_Library,_IMT_Ghaziabad.jpg",
    rate: 1000
  },
  {
    id: 36,
    name: "Gonda",
    description: "Known for religious diversity and proximity to Buddhist pilgrimage sites.",
    category: "religious",
    imageUrl: "cities/Gonda.jpg",
    rate: 800
  },
  {
    id: 37,
    name: "Gorakhpur",
    description: "A spiritual city famous for Gorakhnath Temple and its rich cultural fabric.",
    category: "religious",
    imageUrl: "cities/Gorakhnath_Mandir_in_nutshell.jpg",
    rate: 1000
  },
  {
    id: 38,
    name: "Hamirpur",
    description: "Known for scenic landscapes and historical landmarks.",
    category: "#",
    imageUrl: "cities/Hamirpur.jpg",
    rate: 800
  },
  {
    id: 39,
    name: "Hapur",
    description: "An agricultural hub with growing industrial sectors and urbanization.",
    category: "modern",
    imageUrl: "cities/Brajghat_02.jpg",
    rate: 800
  },
  {
    id: 40,
    name: "Hardoi",
    description: "Famous for its religious sites and agricultural economy.",
    category: "religious",
    imageUrl: "cities/A_tribute_to_the_Martyr_Narpat_Singh.jpg",
    rate: 800
  },
  {
    id: 41,
    name: "Hathras",
    description: "Known for marble carving industry and historical monuments.",
    category: "#",
    imageUrl: "cities/Banke_Bhawan_Residance_of_Kaka_Hathrasi.jpg",
    rate: 800
  },
  {
    id: 42,
    name: "Jalaun",
    description: "A city with rich historical and cultural heritage in Bundelkhand region.",
    category: "#",
    imageUrl: "cities/84_GUMBAD.jpg",
    rate: 800
  },
  {
    id: 43,
    name: "Jaunpur",
    description: "Famous for its Islamic architecture and beautiful mosques.",
    category: "#",
    imageUrl: "cities/Two-floored_exterior_facade_on_the_south,_Jama_Masjid,_Jaunpur,_Uttar_Pradesh,_India_-_20090221.jpg",
    rate: 800
  },
  {
    id: 44,
    name: "Sultanpur",
    description: "Known for its bird sanctuary and agricultural produce.",
    category: "#",
    imageUrl: "cities/Sultanpur.webp",
    rate: 800
  },
  {
    id: 45,
    name: "Kannauj",
    description: "Renowned for its perfume (attar) production and historical legacy.",
    category: "heritage",
    imageUrl: "cities/A_panel_depicting_the_Saptamatrikas_,_Kannauj,9th-10_century,_Pratihara_dynasty.jpg",
    rate: 800
  },
  {
    id: 46,
    name: "Kanpur Dehat",
    description: "A region with a strong agricultural base and historic forts.",
    category: "#",
    imageUrl: "cities/Kanpur Dehat.webp",
    rate: 800
  },
  {
    id: 47,
    name: "Kanpur Nagar",
    description: "Known as the 'Manchester of the East' for its leather and textile industries.",
    category: "#",
    imageUrl: "cities/Kanpur's_Skyline.jpg",
    rate: 1000
  },
  {
    id: 48,
    name: "Kasganj",
    description: "A city with rich cultural festivals and historical importance.",
    category: "#",
    imageUrl: "cities/Weather_2.jpg",
    rate: 800
  },
  {
    id: 49,
    name: "Kaushambi",
    description: "A historic city with archaeological sites from ancient Hindu kingdoms.",
    category: "#",
    imageUrl: "cities/Ghoshitaram_monastery_in_Kosambi.jpg",
    rate: 800
  },
  {
    id: 50,
    name: "Lakhimpur Kheri",
    description: "Known for its agricultural lands and diverse wildlife sanctuaries nearby.",
    category: "#",
    imageUrl: "cities/Mahaparinirvan_7.jpg",
    rate: 800
  },
  {
    id: 51,
    name: "Kushinagar",
    description: "One of the most important Buddhist pilgrimage sites, where Buddha attained Nirvana.",
    category: "religious",
    imageUrl: "cities/Kushinara1.jpg",
    rate: 1000
  },
  {
    id: 52,
    name: "Lalitpur",
    description: "A district rich in tribal culture and historic forts in Bundelkhand.",
    category: "#",
    imageUrl: "cities/Front_side_of_the_Dashavatara_Temple_in_Deogarh.jpg",
    rate: 800
  },
  {
    id: 53,
    name: "Maharajganj",
    description: "A town known for its agricultural importance and historical heritage.",
    category: "#",
    imageUrl: "cities/Maharajganj.jpg",
    rate: 800
  },
  {
    id: 54,
    name: "Mahoba",
    description: "Known for its ancient forts and historical monuments from the Chandela dynasty.",
    category: "#",
    imageUrl: "cities/MAHOBA,_U.P._-_allha.preview.jpg",
    rate: 800
  },
  {
    id: 55,
    name: "Mainpuri",
    description: "Agricultural hub with rich cultural traditions and festivals.",
    category: "#",
    imageUrl: "cities/AsianOpenbillWithPilaglobosa_Mainpuri.jpg",
    rate: 800
  },
  {
    id: 56,
    name: "Mathura",
    description: "Birthplace of Lord Krishna and a major pilgrimage city with colorful festivals.",
    category: "religious",
    imageUrl: "cities/Life_in_colour_-_Thousands_celebrate_Holi_in_Mathura.jpg",
    rate: 1000
  },
  {
    id: 57,
    name: "Mau",
    description: "Known for its textile industry and traditional craftsmanship.",
    category: "#",
    imageUrl: "cities/Mau.jpg",
    rate: 800
  },
  {
    id: 58,
    name: "Meerut",
    description: "Historically famous for its role in 1857 revolt and modern industrial city.",
    category: "heritage",
    imageUrl: "cities/RRTS_train.jpg",
    rate: 1000
  },
  {
    id: 59,
    name: "Mirzapur",
    description: "Known for its carpets, brassware, and historical temples.",
    category: "#",
    imageUrl: "cities/Siddhanth_ki_Dari_Fall.jpg",
    rate: 800
  },
  {
    id: 60,
    name: "Moradabad",
    description: "Renowned worldwide for its brass handicrafts and metal work industries.",
    category: "#",
    imageUrl: "cities/New_Moradabad_Skyline_NH_24.jpg",
    rate: 1000
  },
  {
    id: 61,
    name: "Muzaffarnagar",
    description: "A major agricultural and commercial city with vibrant markets.",
    category: "#",
    imageUrl: "cities/Muzaffarpur_Juction_Railway_Station.jpg",
    rate: 800
  },
  {
    id: 62,
    name: "Pilibhit",
    description: "Known for its tiger reserve and dense forest cover.",
    category: "#",
    imageUrl: "cities/I_love_pilibhit_city.jpg",
    rate: 800
  },
  {
    id: 63,
    name: "Pratapgarh",
    description: "A city famous for mango orchards and historical temples.",
    category: "#",
    imageUrl: "cities/Jakham_River_in_Sita_Mata_WL_Sanctuary_in_Pratapgarh.jpg",
    rate: 800
  },
  {
    id: 64,
    name: "Rae Bareli",
    description: "Known politically and for its rich cultural heritage spanning several centuries.",
    category: "#",
    imageUrl: "cities/Raebareli_Collage.jpg",
    rate: 800
  },
  {
    id: 65,
    name: "Rampur",
    description: "Famous for its cultural heritage, Rampur Raza Library and nawabi era architecture.",
    category: "#",
    imageUrl: "cities/Rampur_collage1.jpg",
    rate: 800
  },
  {
    id: 66,
    name: "Saharanpur",
    description: "Known for its wood carving industry and religious diversity.",
    category: "#",
    imageUrl: "cities/Weeks_Edwin_Lord_Indian_Barbers_Saharanpore.jpg",
    rate: 800
  },
  {
    id: 67,
    name: "Sambhal",
    description: "Famous for its traditional textiles and crafts.",
    category: "#",
    imageUrl: "cities/Perspective_view_of_the_mosque_at_Sambhal,_Uttar_Pradesh_RIBA35777.jpg",
    rate: 800
  },
  {
    id: 68,
    name: "Sant Kabir Nagar",
    description: "A spiritual city dedicated to the saint Kabir with ancient temples and festivals.",
    category: "religious",
    imageUrl: "cities/Nature_photo_of_bakhira_bird_sanctuary_clicked_by_saurabh.jpg",
    rate: 800
  },
  {
    id: 69,
    name: "Shahjahanpur",
    description: "Known for its freedom fighters and rich cultural traditions.",
    category: "#",
    imageUrl: "cities/River_GARRAH_Shahjahanpur,_Uttar_pradesh,_India.jpg",
    rate: 800
  },
  {
    id: 70,
    name: "Shamli",
    description: "A growing industrial city with a strong agricultural base.",
    category: "#",
    imageUrl: "cities/Shamli.jpg",
    rate: 800
  },
  {
    id: 71,
    name: "Shravasti",
    description: "An important Buddhist pilgrimage site associated with Lord Buddha's enlightenment path.",
    category: "religious",
    imageUrl: "cities/Svetambara_Jaina_temple_at_Shravasti.jpg",
    rate: 800
  },
  {
    id: 72,
    name: "Siddharthnagar",
    description: "Named after Lord Buddha, the district holds several ancient Buddhist sites.",
    category: "religious",
    imageUrl: "cities/View_from_the_top_of_Vijay_Garh_Fort.jpg",
    rate: 800
  },
  {
    id: 73,
    name: "Sitapur",
    description: "Known for its religious diversity, temples, and festivals.",
    category: "religious",
    imageUrl: "cities/River_GARRAH_Shahjahanpur,_Uttar_pradesh,_India.jpg",
    rate: 800
  },
  {
    id: 74,
    name: "Sonbhadra",
    description: "Famous for its mineral wealth, forests, and natural beauty.",
    category: "#",
    imageUrl: "cities/SSTPS.jpg",
    rate: 800
  },
  {
    id: 75,
    name: "Ghazipur",
    description: "Known for its perfume industry and rich cultural heritage with historical significance.",
    category: "#",
    imageUrl: "cities/View_of_Noida_city_from_the_Hilton_Noida.jpg",
    rate: 800
  }
];


const packagesData = [
  {
    id: 1,
    name: "Heritage Tour",
    description: "5 Days covering Agra, Lucknow, and Fatehpur Sikri",
    price: 12999,
    duration: "5 Days / 4 Nights",
    locations: "Agra → Lucknow → Fatehpur Sikri",
    minPersons: 2,
    imageUrl: "ExploreUP/photo/home4.jpg"
  },
  {
    id: 2,
    name: "Spiritual Journey",
    description: "7 Days spiritual tour of Varanasi, Mathura, and Vrindavan",
    price: 8999,
    duration: "7 Days / 6 Nights",
    locations: "Varanasi → Mathura → Vrindavan",
    minVerdict: 2,
    imageUrl: "https://images.unsplash.com/photo-1544735716-392fe2489ffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=300"
  },
  {
    id: 3,
    name: "Cultural Experience",
    description: "10 Days exploring culture, cuisine, and crafts across UP",
    price: 15999,
    duration: "10 Days / 9 Nights",
    locations: "Multiple Cities",
    minPersons: 4,
    imageUrl: "ExploreUP/photo/home3.jpg"
  }
];

const reviewsData = [
  {
    id: 1,
    name: "Priya Sharma",
    destination: "Varanasi",
    rating: 5,
    text: "An absolutely spiritual experience! The ghats, the evening aarti, and the boat ride on Ganges were unforgettable. The guide was knowledgeable and the arrangements were perfect.",
    date: "2024-12-15"
  },
  {
    id: 2,
    name: "Amit Kumar",
    destination: "Agra",
    rating: 5,
    text: "The Taj Mahal is even more beautiful in person! Our tour included early morning access which was amazing. Great photography opportunities and excellent local food recommendations.",
    date: "2024-12-10"
  },
  {
    id: 3,
    name: "Sneha Gupta",
    destination: "Lucknow",
    rating: 4,
    text: "Loved the Nawabi culture and cuisine! The Imambara was magnificent and the kebabs were delicious. Would definitely recommend this trip to food lovers.",
    date: "2024-12-08"
  },
  {
    id: 4,
    name: "Rajesh Patel",
    destination: "Prayagraj",
    rating: 5,
    text: "The Sangam was a divine experience. The spiritual atmosphere and the boat ride to the confluence point were highlights. Well organized trip with comfortable accommodations.",
    date: "2024-12-05"
  },
  {
    id: 5,
    name: "Kavita Singh",
    destination: "Mathura",
    rating: 4,
    text: "Beautiful temples and rich Krishna heritage. The local guides shared amazing stories and the festival atmosphere was incredible. Great for families and devotees.",
    date: "2024-12-02"
  },
  {
    id: 6,
    name: "Suresh Yadav",
    destination: "Gorakhpur",
    rating: 4,
    text: "Peaceful pilgrimage destination with beautiful temples. The Gorakhnath Temple is a must-visit. Good connectivity and clean facilities.",
    date: "2024-11-28"
  },
  {
    id: 7,
    name: "Meera Joshi",
    destination: "Varanasi",
    rating: 4,
    text: "The sunrise boat ride was magical. Ganga aarti was crowded but worth experiencing. Great spiritual atmosphere.",
    date: "2024-11-20"
  },
  {
    id: 8,
    name: "Rohan Mishra",
    destination: "Agra",
    rating: 4,
    text: "Taj Mahal was breathtaking! Also visited Agra Fort. Local guide was very informative about Mughal history.",
    date: "2024-11-18"
  },
  {
    id: 9,
    name: "Anita Verma",
    destination: "Lucknow",
    rating: 5,
    text: "Amazing Awadhi cuisine and beautiful architecture. Bara Imambara was fascinating. Highly recommend for food lovers!",
    date: "2024-11-15"
  },
  {
    id: 10,
    name: "Vikram Singh",
    destination: "Jhansi",
    rating: 4,
    text: "Rich historical significance. Jhansi Fort was impressive. Good place to learn about Rani Lakshmibai's story.",
    date: "2024-11-12"
  },
  {
    id: 11,
    name: "Sunita Gupta",
    destination: "Ayodhya",
    rating: 5,
    text: "Very peaceful and spiritual. Ram Janmabhoomi was beautiful. Well-organized pilgrimage experience.",
    date: "2024-11-10"
  },
  {
    id: 12,
    name: "Arjun Sharma",
    destination: "Gautam Buddha Nagar (Noida)",
    rating: 3,
    text: "Modern city with good infrastructure. Great for business trips. Worlds of Wonder was fun for families.",
    date: "2024-11-08"
  },
  {
    id: 13,
    name: "Deepika Rai",
    destination: "Mathura",
    rating: 5,
    text: "Krishna Janmabhoomi was divine. Celebrated Holi here - absolutely magical experience! Must visit for devotees.",
    date: "2024-11-05"
  },
  {
    id: 14,
    name: "Manish Kumar",
    destination: "Kushinagar",
    rating: 4,
    text: "Important Buddhist pilgrimage site. Very peaceful and well-maintained. Great for meditation and spiritual peace.",
    date: "2024-11-02"
  }
];

let currentFilter = 'all';
let searchTimeout;

function toggleTheme() {
  const body = document.body;
  const currentTheme = body.getAttribute('data-theme');
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

  body.setAttribute('data-theme', newTheme);
  localStorage.setItem('theme', newTheme);

  const themeIcon = document.querySelector('.theme-btn i');
  themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
}

function initializeTheme() {
  const savedTheme = localStorage.getItem('theme') || 'light';
  document.body.setAttribute('data-theme', savedTheme);

  const themeIcon = document.querySelector('.theme-btn i');
  themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
}

function searchCities() {
  const searchInput = document.getElementById('search-input');
  const searchResults = document.getElementById('search-results');
  const query = searchInput.value.toLowerCase().trim();

  clearTimeout(searchTimeout);

  if (query.length === 0) {
    searchResults.style.display = 'none';
    return;
  }

  if (query.length < 2) {
    return; // Wait for at least 2 characters
  }

  searchTimeout = setTimeout(() => {
    const filteredCities = citiesData.filter(city => {
      const cityName = city.name.toLowerCase();
      const cityDesc = city.description.toLowerCase();
      const cityCategory = city.category.toLowerCase();

      // Exact match gets highest priority
      if (cityName === query) return true;

      // Start of word match
      if (cityName.startsWith(query)) return true;

      // Contains match in name
      if (cityName.includes(query)) return true;

      // Contains match in description
      if (cityDesc.includes(query)) return true;

      // Category match
      if (cityCategory.includes(query)) return true;

      // Fuzzy matching for common variations
      if (query === 'ayodha' && cityName === 'ayodhya') return true;
      if (query === 'mathura' && cityName === 'mathura') return true;
      if (query === 'banaras' && cityName === 'varanasi') return true;
      if (query === 'kashi' && cityName === 'varanasi') return true;
      if (query === 'allahabad' && cityName === 'prayagraj') return true;

      return false;
    });

    // Sort results by relevance
    filteredCities.sort((a, b) => {
      const aName = a.name.toLowerCase();
      const bName = b.name.toLowerCase();

      // Exact matches first
      if (aName === query && bName !== query) return -1;
      if (bName === query && aName !== query) return 1;

      // Starts with query
      if (aName.startsWith(query) && !bName.startsWith(query)) return -1;
      if (bName.startsWith(query) && !aName.startsWith(query)) return 1;

      // Alphabetical order for remaining
      return aName.localeCompare(bName);
    });

    displaySearchResults(filteredCities);
  }, 300);
}

function displaySearchResults(cities) {
  const searchResults = document.getElementById('search-results');

  if (cities.length === 0) {
    searchResults.innerHTML = '<div class="result-item" style="color: #6b7280; font-style: italic;">No cities found</div>';
  } else {
    // Limit to top 5 results for better UX
    const topResults = cities.slice(0, 5);
    searchResults.innerHTML = topResults.map(city => `
            <div class="result-item" onclick="selectSearchResult('${city.name}')" style="cursor: pointer;">
                <div style="font-weight: 500; color: var(--primary-color);">${city.name}</div>
                <div style="font-size: 0.875rem; color: var(--text-light);">${city.description.substring(0, 70)}...</div>
                <div style="font-size: 0.75rem; color: var(--primary-color); margin-top: 4px;">
                    <i class="fas fa-map-marker-alt"></i> ${city.category} • ₹${city.rate}/day
                </div>
            </div>
        `).join('');

    if (cities.length > 5) {
      const remainingCount = cities.length - 5;
      searchResults.innerHTML += `
        <div style="text-align: center; padding: 8px; color: #6b7280; font-size: 0.8rem; border-top: 1px solid #0655f4ff;">
          +${remainingCount} more result${remainingCount > 1 ? 's' : ''} available
        </div>
      `;
    }
  }

  searchResults.style.display = 'block';
}

function selectSearchResult(cityName) {
  document.getElementById('search-input').value = cityName;
  document.getElementById('search-results').style.display = 'none';

  // Find the city in the data
  const selectedCity = citiesData.find(city =>
    city.name.toLowerCase() === cityName.toLowerCase()
  );

  if (selectedCity) {
    // Get the index of the selected city in the original array
    const cityIndex = citiesData.indexOf(selectedCity);

    // Clear any previous filters and show all cities
    currentFilter = 'all';

    // Make sure we show enough cities to include the selected one
    // Add 1 to cityIndex because array is 0-based but we want to show that many cities
    citiesShown = Math.max(6, cityIndex + 1);

    // Update filter buttons to show "All Cities" as active
    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.classList.remove('active');
      if (btn.textContent === 'All Citys') {
        btn.classList.add('active');
      }
    });

    // Redisplay cities with the updated count
    displayCities();

    // Scroll to cities section first
    document.getElementById('cities').scrollIntoView({ behavior: 'smooth' });

    // Wait for the display to update, then highlight the selected city
    setTimeout(() => {
      const cityCards = document.querySelectorAll('.city-card');
      cityCards.forEach(card => {
        const cardTitle = card.querySelector('h3');
        if (cardTitle && cardTitle.textContent.trim() === selectedCity.name.trim()) {
          // Add highlight effect
          card.style.border = '3px solid var(--primary-color)';
          card.style.boxShadow = '0 8px 25px rgba(114, 152, 235, 0.3)';
          card.style.transform = 'scale(1.05)';
          card.style.transition = 'all 0.01s ease ';
          card.style.backgroundColor = '#000000ff';

          // Scroll to the specific card
          card.scrollIntoView({ behavior: 'smooth', block: 'center' });

          // Remove highlight after 4 seconds
          setTimeout(() => {
            card.style.border = '';
            card.style.boxShadow = '';
            card.style.transform = '';
            card.style.backgroundColor = '';
          }, 1500);
        }
      });
    }, 1000);
  } else {
    // If city not found, just go to cities section
    document.getElementById('cities').scrollIntoView({ behavior: 'smooth' });
  }
}

document.addEventListener('click', function (event) {
  const searchArea = document.querySelector('.search-area');
  if (!searchArea.contains(event.target)) {
    document.getElementById('search-results').style.display = 'none';
  }
});

function filterCities(category, event) {
  currentFilter = category;

  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  event.target.classList.add('active');

  displayCities();
}

let citiesShown = 6;

function getCityReviewStats(cityName) {
  // Filter reviews for this specific city
  const cityReviews = reviewsData.filter(review =>
    review.destination.toLowerCase() === cityName.toLowerCase()
  );

  if (cityReviews.length === 0) {
    return { averageRating: 0, reviewCount: 0 };
  }

  // Calculate average rating
  const totalRating = cityReviews.reduce((sum, review) => sum + review.rating, 0);
  const averageRating = Math.round((totalRating / cityReviews.length) * 10) / 10; // Round to 1 decimal

  return {
    averageRating: averageRating,
    reviewCount: cityReviews.length
  };
}

function displayCities() {
  const citiesGrid = document.getElementById('cities-grid');
  let filteredCities = citiesData;

  if (currentFilter !== 'all') {
    filteredCities = citiesData.filter(city =>
      city.category.toLowerCase().includes(currentFilter.toLowerCase())
    );
  }

  const visibleCities = filteredCities.slice(0, citiesShown);
  citiesGrid.innerHTML = visibleCities.map(city => {
    const reviewStats = getCityReviewStats(city.name);
    const displayRating = reviewStats.averageRating > 0 ? reviewStats.averageRating : 4.2; // Default rating if no reviews
    const displayReviewCount = reviewStats.reviewCount;

    return `
        <div class="city-card">
            <img src="${city.imageUrl}" alt="${city.name}" loading="lazy">
            <div class="card-text">
                <span class="category">${city.category}</span>
                <h3>${city.name}</h3>
                <p>${city.description}</p>
                <div class="rating">
                    <span class="stars">${generateStars(displayRating)}</span>
                    <span class="rating-text">${displayRating} (${displayReviewCount} review${displayReviewCount !== 1 ? 's' : ''})</span>
                </div>
                <button class="btn-primary" onclick="openBookingModal('${city.name}')">
                    Book Trip
                </button>
            </div>
        </div>
    `;
  }).join('');

  const showMoreBtn = document.getElementById('show-more-btn');
  if (filteredCities.length > citiesShown) {
    showMoreBtn.style.display = 'block';
  } else {
    showMoreBtn.style.display = 'none';
  }
}

function loadMoreCities() {
  citiesShown += 6;
  displayCities();
}

function displayPackages() {
  const packagesGrid = document.getElementById('packages-grid');

  packagesGrid.innerHTML = packagesData.map(pkg => `
        <div class="package-card">
            <img src="${pkg.imageUrl}" alt="${pkg.name}" loading="lazy">
            <div class="package-text">
                <h3>${pkg.name}</h3>
                <div class="package-price">₹${pkg.price.toLocaleString()}</div>
                <p>${pkg.description}</p>
                <div class="package-info">
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span>${pkg.duration}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${pkg.locations}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-users"></i>
                        <span>Min ${pkg.minPersons} persons</span>
                    </div>
                </div>
                <button class="btn-primary" onclick="openBookingModal('${pkg.name}')">
                    Book Package
                </button>
            </div>
        </div>
    `).join('');
}

function generateStars(rating) {
  const fullStars = Math.floor(rating);
  const hasHalfStar = (rating % 1) >= 0.5;
  let stars = '';

  // Add full stars
  for (let i = 0; i < fullStars; i++) {
    stars += '<i class="fas fa-star"></i>';
  }

  // Add half star if needed
  if (hasHalfStar && fullStars < 5) {
    stars += '<i class="fas fa-star-half-alt"></i>';
  }

  // Add empty stars
  const totalFilledStars = fullStars + (hasHalfStar ? 1 : 0);
  const emptyStars = 5 - totalFilledStars;
  for (let i = 0; i < emptyStars; i++) {
    stars += '<i class="far fa-star"></i>';
  }

  return stars;
}

function openBookingModal(itemName) {
  const city = citiesData.find(c => c.name === itemName);
  const package = packagesData.find(p => p.name === itemName);

  if (city) {
    document.getElementById("destination").value = city.name;
    document.getElementById("rate").value = `₹${city.rate}/Day`;
    document.getElementById("total-amount").textContent = "₹0";
    document.getElementById("booking-modal").style.display = "block";

    const checkIn = document.getElementById("check-in");
    const checkOut = document.getElementById("check-out");

    function calculateTotal() {
      const adults = parseInt(document.getElementById("adults").value) || 1;
      const children = parseInt(document.getElementById("children").value) || 0;

      if (checkIn.value && checkOut.value) {
        const start = new Date(checkIn.value);
        const end = new Date(checkOut.value);
        const days = (end - start) / (1000 * 60 * 60 * 24);

        if (days > 0) {
          let baseAmount = days * city.rate;
          let additionalCharges = 0;

          if (adults > 1) {
            additionalCharges += (adults - 1) * 800 * days;
          }

          if (children > 0) {
            additionalCharges += children * 500 * days;
          }

          const gstOnBase = Math.round(baseAmount * 0.18);
          const totalAmount = baseAmount + gstOnBase + additionalCharges;
          document.getElementById("total-amount").textContent = `₹${totalAmount}`;
        } else {
          document.getElementById("total-amount").textContent = "Invalid dates";
        }
      } else {
        let baseAmount = city.rate;
        let additionalCharges = 0;

        if (adults > 1) {
          additionalCharges += (adults - 1) * 800;
        }

        if (children > 0) {
          additionalCharges += children * 500;
        }

        const gstOnBase = Math.round(baseAmount * 0.18);
        const totalAmount = baseAmount + gstOnBase + additionalCharges;
        document.getElementById("total-amount").textContent = `₹${totalAmount}`;
      }
    }

    calculateTotal();

    checkIn.addEventListener("change", calculateTotal);
    checkOut.addEventListener("change", calculateTotal);
    document.getElementById("adults").addEventListener("change", calculateTotal);
    document.getElementById("children").addEventListener("change", calculateTotal);
  } else if (package) {
    document.getElementById("destination").value = package.name;
    document.getElementById("rate").value = `₹${package.price}/Person`;

    function calculatePackageTotal() {
      let baseAmount = package.price;
      let additionalCharges = 0;

      const adults = parseInt(document.getElementById("adults").value) || 1;
      const children = parseInt(document.getElementById("children").value) || 0;

      if (adults > 1) {
        additionalCharges += (adults - 1) * 800;
      }

      if (children > 0) {
        additionalCharges += children * 500;
      }

      const gstOnBase = Math.round(baseAmount * 0.18);
      const totalAmount = baseAmount + gstOnBase + additionalCharges;
      document.getElementById("total-amount").textContent = `₹${totalAmount}`;
    }

    calculatePackageTotal();
    document.getElementById("adults").addEventListener("change", calculatePackageTotal);
    document.getElementById("children").addEventListener("change", calculatePackageTotal);
    document.getElementById("booking-modal").style.display = "block";

    const checkIn = document.getElementById("check-in");
    const checkOut = document.getElementById("check-out");

    const durationMatch = package.duration.match(/(\d+)\s+Days/);
    const packageDays = durationMatch ? parseInt(durationMatch[1]) : 1;

    function calculatePackageDates() {
      document.getElementById("total-amount").textContent = `₹${package.price}`;

      if (checkIn.value) {
        const startDate = new Date(checkIn.value);
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + packageDays);

        const formattedEndDate = endDate.toISOString().split('T')[0];
        checkOut.value = formattedEndDate;

        checkOut.readOnly = true;
        checkOut.style.backgroundColor = '#f5f5f5';
        checkOut.style.cursor = 'not-allowed';
        checkOut.title = `Auto-calculated: ${packageDays} days from check-in date`;
      }
    }

    checkIn.removeEventListener("change", calculatePackageDates);
    checkOut.removeEventListener("change", calculatePackageDates);

    checkIn.addEventListener("change", calculatePackageDates);
    checkOut.addEventListener("click", function() {
      if (this.readOnly) {
        alert(`Check-out date is automatically set for ${package.name} (${package.duration}). Please change the check-in date if needed.`);
      }
    });
  }
}

function closeBookingModal() {
  const modal = document.getElementById('booking-modal');
  modal.style.display = 'none';

  document.getElementById('booking-form').reset();

  const checkOut = document.getElementById('check-out');
  checkOut.readOnly = false;
  checkOut.style.backgroundColor = '';
  checkOut.style.cursor = '';
  checkOut.title = '';
}

function proceedToPayment() {
  const destination = document.getElementById('destination').value;
  const checkIn = document.getElementById('check-in').value;
  const checkOut = document.getElementById('check-out').value;
  const adults = document.getElementById('adults').value;
  const children = document.getElementById('children').value;
  const tripType = document.getElementById('trip-type').value;
  const specialRequests = document.getElementById('special-requests').value;
  const totalAmount = document.getElementById('total-amount').textContent;
  const policyAccepted = document.getElementById('policy-accept').checked;

  if (!destination || !checkIn || !checkOut) {
    alert('Please fill in all required fields');
    return;
  }

  if (!policyAccepted) {
    alert('Please accept the Booking Policy & Terms to continue');
    return;
  }

  if (window.bookingInProgress) {
    return;
  }

  window.bookingInProgress = true;

  const isPackage = packagesData.find(p => p.name === destination);
  const bookingType = isPackage ? 'package' : 'city';

  const paymentAmount = parseFloat(totalAmount.replace('₹', '').replace(',', ''));

  const formData = new FormData();
  formData.append('city', destination);
  formData.append('checkin_date', checkIn);
  formData.append('checkout_date', checkOut);
  formData.append('adults', adults);
  formData.append('children', children);
  formData.append('trip_type', tripType);
  formData.append('special_requests', specialRequests);
  formData.append('payment_method', 'pending');
  formData.append('payment_amount', paymentAmount);
  formData.append('payment_status', 'pending');

  fetch('fiels/booking.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    window.bookingInProgress = false;

    if (data.status === 'success') {
      const paymentData = {
        bookingType: 'existing',
        bookingId: data.booking_id,
        destination: destination,
        checkIn: checkIn,
        checkOut: checkOut,
        adults: adults,
        children: children,
        tripType: tripType,
        specialRequests: specialRequests,
        payment_amount: paymentAmount,
        totalAmount: totalAmount
      };

      sessionStorage.setItem('bookingData', JSON.stringify(paymentData));

      const paymentWindow = window.open('payment.html');

      const messageHandler = function(event) {
        if (event.data && event.data.type === 'paymentSuccess') {
          window.removeEventListener('message', messageHandler);
          closeBookingModal();
          alert('🎉 Payment successful! Your booking is now being processed.');
          document.getElementById('booking-form').reset();
        }
      };

      window.addEventListener('message', messageHandler);

      const checkClosed = setInterval(() => {
        if (paymentWindow.closed) {
          clearInterval(checkClosed);
          window.removeEventListener('message', messageHandler);
        }
      }, 1000);

    } else {
      alert('Booking failed: ' + (data.message || 'Please try again'));
    }
  })
  .catch(error => {
    window.bookingInProgress = false;
    console.error('Error:', error);
    alert('An error occurred while creating your booking. Please try again.');
  });
}

window.addEventListener('click', function (event) {
  const modals = [
    { id: 'booking-modal', resetFormId: 'booking-form' }
  ];

  modals.forEach(({ id, resetFormId }) => {
    const modal = document.getElementById(id);
    if (modal && event.target === modal) {
      modal.style.display = 'none';
      if (resetFormId) {
        const form = document.getElementById(resetFormId);
        if (form) form.reset();
      }
    }
  });
});

document.addEventListener('DOMContentLoaded', function() {
  initializeTheme();
  displayCities();
  displayPackages();
  loadReviews();

  const today = new Date().toISOString().split('T')[0];
  const checkInInput = document.getElementById('check-in');
  const checkOutInput = document.getElementById('check-out');
  const bookingForm = document.getElementById('booking-form');

  if (checkInInput) {
    checkInInput.min = today;

    checkInInput.addEventListener('change', function () {
      const checkInDate = this.value;
      if (checkOutInput && checkInDate) {
        const minCheckOut = new Date(checkInDate);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        checkOutInput.min = minCheckOut.toISOString().split('T')[0];
      }
    });
  }

  if (bookingForm) {
    bookingForm.addEventListener('submit', submitBooking);
  }

  setTimeout(() => {
    if (document.body) {
      document.body.classList.add('loaded');
    }
  }, 100);

  const sections = document.querySelectorAll('section');
  if (sections.length > 0) {
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };
    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animation = 'fadeIn 0.6s ease-out forwards';
        }
      });
    }, observerOptions);
    sections.forEach(section => observer.observe(section));
  }
});

function submitBooking(event) {
  event.preventDefault();

  if (window.bookingInProgress) {
    return;
  }

  const policyAccepted = document.getElementById('policy-accept').checked;
  if (!policyAccepted) {
    alert('Please accept the Booking Policy & Terms to continue');
    return;
  }

  proceedToPayment();
}

function submitBookingWithPayment(bookingData) {
  if (window.bookingInProgress) {
    return;
  }

  window.bookingInProgress = true;

  const formData = new FormData();
  formData.append('city', bookingData.destination);
  formData.append('checkin_date', bookingData.checkIn);
  formData.append('checkout_date', bookingData.checkOut);
  formData.append('adults', bookingData.adults);
  formData.append('children', bookingData.children);
  formData.append('trip_type', bookingData.tripType);
  formData.append('special_requests', bookingData.specialRequests || '');
  formData.append('payment_method', bookingData.payment_method || '');
  formData.append('payment_amount', bookingData.payment_amount || 0);
  formData.append('payment_status', bookingData.payment_status || 'completed');

  fetch('fiels/booking.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      window.bookingInProgress = false;

      if (data.status === 'success') {
        closeBookingModal();
        alert('🎉 Booking confirmed and payment completed successfully! Your trip has been booked.');
        sessionStorage.removeItem('bookingData');
        document.getElementById('booking-form').reset();
      } else {
        alert('Booking confirmation failed: ' + (data.message || 'Please contact support'));
      }
    })
    .catch(error => {
      window.bookingInProgress = false;
      console.error('Error:', error);
      alert('An error occurred. Please try again later.');
    });
}

function createParticles() {
  const particles = document.createElement('div');
  particles.className = 'particles-container';
  particles.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1000;
  `;

  for (let i = 0; i < 50; i++) {
    const particle = document.createElement('div');
    particle.style.cssText = `
      position: absolute;
      width: 4px;
      height: 4px;
      background: #ff6b6b;
      border-radius: 50%;
      left: ${Math.random() * 100}%;
      top: ${Math.random() * 100}%;
      opacity: 0.7;
      animation: float ${Math.random() * 3 + 2}s infinite ease-in-out;
    `;
    particles.appendChild(particle);
  }

  document.body.appendChild(particles);

  setTimeout(() => {
    if (particles.parentNode) {
      particles.parentNode.removeChild(particles);
    }
  }, 5000);
}

const style = document.createElement('style');
style.textContent = `
  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
    50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
  }
`;
document.head.appendChild(style);

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

function toggleChat() {
  const popup = document.getElementById('chat-box');
  const isVisible = popup.style.display === 'block';

  if (isVisible) {
    popup.classList.remove('show');
    setTimeout(() => {
      popup.style.display = 'none';
    }, 300);
  } else {
    popup.style.display = 'block';
    popup.offsetHeight;
    popup.classList.add('show');
  }
}

function showResponse(question) {
  const chatBody = document.getElementById('chat-body');

  addMessage(chatBody, question, 'user-msg');

  const typingIndicator = createTypingIndicator();
  chatBody.appendChild(typingIndicator);
  scrollToBottom(chatBody);

  setTimeout(() => {
    typingIndicator.remove();
    const answer = getAnswer(question);
    addMessage(chatBody, answer, 'bot-msg');
    scrollToBottom(chatBody);
  }, 1500);
}

function addMessage(container, text, className) {
  const message = document.createElement('div');
  message.className = className;
  message.textContent = text;
  container.appendChild(message);
}

function createTypingIndicator() {
  const typing = document.createElement('div');
  typing.className = 'bot-msg';

  const dots = document.createElement('div');
  dots.className = 'typing-dots';

  for (let i = 0; i < 3; i++) {
    const dot = document.createElement('div');
    dots.appendChild(dot);
  }

  typing.appendChild(dots);
  return typing;
}

function scrollToBottom(element) {
  setTimeout(() => {
    element.scrollTo({
      top: element.scrollHeight,
      behavior: 'smooth'
    });
  }, 100);
}

function getAnswer(question) {
  const answers = {
    'What is the best time to visit UP?': '🌤️ Best time: October to March (cool & pleasant weather)\n• Winter (Dec-Feb): Ideal for sightseeing\n• Avoid: April-June (very hot)\n• Monsoon (July-Sep): Good but expect rain',
    'How do I get around UP?': '🚌 Transportation options:\n• Trains: Well-connected railway network\n• Buses: State & private buses available\n• Taxis/Cabs: Ola, Uber, local taxis\n• Auto-rickshaws: For short distances',
    'What should I pack for my trip?': '🎒 Essential packing list:\n• Comfortable walking shoes\n• Light cotton clothes (summer) / Warm clothes (winter)\n• Sunscreen & hat\n• Power bank & charger\n• Basic medicines\n• Valid ID documents',
    'Are there budget-friendly options?': '💰 Budget travel tips:\n• Choose group packages\n• Stay in budget hotels/guesthouses\n• Use public transport\n• Eat at local restaurants\n• Visit free attractions like ghats & temples',
    'What local food should I try?': '🍛 Must-try UP cuisine:\n• Lucknowi kebabs & biriyani\n• Varanasi chaat & kachori\n• Agra petha (sweet)\n• Awadhi cuisine\n• Street food in old city areas\n• Fresh lassi & kulfi',
    'How can I contact customer support?': '📞 Contact us anytime:\n• Phone: +91 9876543211\n• Email: knowup65@gmail.com\n (bottom-left)\n• Response time: Within 2 hours'
  };
  return answers[question] || "🤔 That's a great question! Please contact our support team for detailed assistance. We're here to help!";
}

// Global variable to store reviews for rating calculations
let allReviewsData = [];

function loadReviews() {
  const reviewsGrid = document.getElementById('reviews-grid');
  if (!reviewsGrid) return;

  fetch('fiels/get_reviews.php')
    .then(response => {
      if (!response.ok) throw new Error('Failed to fetch reviews');
      return response.json();
    })
    .then(data => {
      if (data.error) throw new Error('Database error');

      // Store all reviews for rating calculations
      if (data.length > 0) {
        allReviewsData = data;
        // Override sample data with real data for city ratings
        reviewsData.length = 0;
        reviewsData.push(...data);
      } else {
        allReviewsData = reviewsData;
      }

      const reviews = data.length > 0 ? data.slice(0, 3) : reviewsData.slice(0, 3);

      reviewsGrid.innerHTML = reviews.map(review => `
        <div class="review-card">
          <div class="review-header">
            <div class="user-avatar">${(review.name || review.user_name || 'User').charAt(0).toUpperCase()}</div>
            <div class="user-info">
              <h4>${review.name || review.user_name || 'Anonymous User'}</h4>
              <p>${new Date(review.date || review.created_at).toLocaleDateString('en-IN', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
              })}</p>
            </div>
          </div>
          <div class="review-rating">
            ${Array.from({length: review.rating}, () => '<span class="star">★</span>').join('')}
            ${Array.from({length: 5 - review.rating}, () => '<span class="star" style="color: #e5e5e5;">★</span>').join('')}
          </div>
          <p class="review-text">"${review.text || review.review_text}"</p>
          <div class="review-place">
            <i class="fas fa-map-marker-alt" style="margin-right: 5px; color: var(--primary-color);"></i>
            ${review.destination}
          </div>
        </div>
      `).join('');

      // Refresh city display to show updated ratings
      displayCities();
    })
    .catch(error => {
      console.log('Loading sample reviews as fallback');
      allReviewsData = reviewsData;
      const homeReviews = reviewsData.slice(0, 3);

      reviewsGrid.innerHTML = homeReviews.map(review => `
        <div class="review-card">
          <div class="review-header">
            <div class="user-avatar">${review.name.charAt(0).toUpperCase()}</div>
            <div class="user-info">
              <h4>${review.name}</h4>
              <p>${new Date(review.date).toLocaleDateString('en-IN', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
              })}</p>
            </div>
          </div>
          <div class="review-rating">
            ${Array.from({length: review.rating}, () => '<span class="star">★</span>').join('')}
            ${Array.from({length: 5 - review.rating}, () => '<span class="star" style="color: #e5e5e5;">★</span>').join('')}
          </div>
          <p class="review-text">"${review.text}"</p>
          <div class="review-place">
            <i class="fas fa-map-marker-alt" style="margin-right: 5px; color: var(--primary-color);"></i>
            ${review.destination}
          </div>
        </div>
      `).join('');

      displayCities();
    });
}