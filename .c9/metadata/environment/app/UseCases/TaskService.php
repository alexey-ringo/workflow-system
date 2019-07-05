{"changed":true,"filter":false,"title":"TaskService.php","tooltip":"/app/UseCases/TaskService.php","value":"<?php\n\nnamespace App\\UseCases;\n\nuse App\\Models\\Task;\nuse App\\Models\\User;\nuse App\\UseCase\\UserService;\n\nclass TaskService\n{\n    public function __construct()\n    {\n        \n    }\n    \n    public static function createNewTask(string $title, string $description, string $password, User $user): self\n    {\n        return Task::create([\n            'task' => self::createTaskNum(),\n            'sequence' => 1/*self::createTaskSequence()*/,\n            'title' => $title,\n            'description' => $description,\n            'status' => 1,\n            'mission_id' => 1,\n            'user_id' => $user->id,\n            'mission_name' => $request->get('sequence'),\n            'user_name' => $request->get('sequence'),\n            'user_email' => $request->get('sequence'),\n            'deadline' => $request->get('sequence')\n        ]);\n    }\n    \n    public static function сreateTaskNum(): int \n    {\n        $lastTask = Task::select('task')->max();\n        \n        return $lastTask ? $lastTask : $lastTask + 1;\n    }\n    \n    public function сreateTaskSequence() {\n      \n    }\n    \n}","undoManager":{"mark":90,"position":100,"stack":[[{"start":{"row":16,"column":15},"end":{"row":16,"column":16},"action":"remove","lines":["s"],"id":39},{"start":{"row":16,"column":15},"end":{"row":16,"column":16},"action":"remove","lines":["t"]},{"start":{"row":16,"column":15},"end":{"row":16,"column":16},"action":"remove","lines":["a"]},{"start":{"row":16,"column":15},"end":{"row":16,"column":16},"action":"remove","lines":["t"]},{"start":{"row":16,"column":15},"end":{"row":16,"column":16},"action":"remove","lines":["i"]},{"start":{"row":16,"column":15},"end":{"row":16,"column":16},"action":"remove","lines":["c"]}],[{"start":{"row":16,"column":15},"end":{"row":16,"column":16},"action":"insert","lines":["T"],"id":40},{"start":{"row":16,"column":16},"end":{"row":16,"column":17},"action":"insert","lines":["a"]},{"start":{"row":16,"column":17},"end":{"row":16,"column":18},"action":"insert","lines":["s"]},{"start":{"row":16,"column":18},"end":{"row":16,"column":19},"action":"insert","lines":["k"]}],[{"start":{"row":17,"column":12},"end":{"row":22,"column":42},"action":"remove","lines":["'name' => $name,","            'email' => $email,","            'password' => bcrypt($password),","            'verify_token' => Str::uuid(),","            'role' => self::ROLE_USER,","            'status' => self::STATUS_WAIT,"],"id":41}],[{"start":{"row":17,"column":12},"end":{"row":27,"column":51},"action":"insert","lines":["'task' => $taskService->createTaskNum(),","            'sequence' => $taekService->createTaskSequence(),","            'title' => $request->get('title'),","            'description' => $request->get('description'),","            'status' => $request->get('status'),","            'mission_id' => $request->get('sequence'),","            'user_id' => $request->get('sequence'),","            'mission_name' => $request->get('sequence'),","            'user_name' => $request->get('sequence'),","            'user_email' => $request->get('sequence'),","            'deadline' => $request->get('sequence')"],"id":42}],[{"start":{"row":14,"column":42},"end":{"row":14,"column":43},"action":"remove","lines":["n"],"id":43},{"start":{"row":14,"column":42},"end":{"row":14,"column":43},"action":"remove","lines":["a"]},{"start":{"row":14,"column":42},"end":{"row":14,"column":43},"action":"remove","lines":["m"]},{"start":{"row":14,"column":42},"end":{"row":14,"column":43},"action":"remove","lines":["e"]}],[{"start":{"row":14,"column":42},"end":{"row":14,"column":43},"action":"insert","lines":["t"],"id":44},{"start":{"row":14,"column":43},"end":{"row":14,"column":44},"action":"insert","lines":["i"]},{"start":{"row":14,"column":44},"end":{"row":14,"column":45},"action":"insert","lines":["t"]},{"start":{"row":14,"column":45},"end":{"row":14,"column":46},"action":"insert","lines":["l"]},{"start":{"row":14,"column":46},"end":{"row":14,"column":47},"action":"insert","lines":["e"]}],[{"start":{"row":14,"column":57},"end":{"row":14,"column":58},"action":"remove","lines":["e"],"id":45},{"start":{"row":14,"column":57},"end":{"row":14,"column":58},"action":"remove","lines":["m"]},{"start":{"row":14,"column":57},"end":{"row":14,"column":58},"action":"remove","lines":["a"]},{"start":{"row":14,"column":57},"end":{"row":14,"column":58},"action":"remove","lines":["i"]},{"start":{"row":14,"column":57},"end":{"row":14,"column":58},"action":"remove","lines":["l"]}],[{"start":{"row":14,"column":57},"end":{"row":14,"column":58},"action":"insert","lines":["d"],"id":46},{"start":{"row":14,"column":58},"end":{"row":14,"column":59},"action":"insert","lines":["e"]},{"start":{"row":14,"column":59},"end":{"row":14,"column":60},"action":"insert","lines":["s"]},{"start":{"row":14,"column":60},"end":{"row":14,"column":61},"action":"insert","lines":["c"]}],[{"start":{"row":14,"column":61},"end":{"row":14,"column":62},"action":"insert","lines":["r"],"id":47},{"start":{"row":14,"column":62},"end":{"row":14,"column":63},"action":"insert","lines":["i"]},{"start":{"row":14,"column":63},"end":{"row":14,"column":64},"action":"insert","lines":["p"]},{"start":{"row":14,"column":64},"end":{"row":14,"column":65},"action":"insert","lines":["t"]},{"start":{"row":14,"column":65},"end":{"row":14,"column":66},"action":"insert","lines":["i"]},{"start":{"row":14,"column":66},"end":{"row":14,"column":67},"action":"insert","lines":["o"]},{"start":{"row":14,"column":67},"end":{"row":14,"column":68},"action":"insert","lines":["n"]}],[{"start":{"row":19,"column":24},"end":{"row":19,"column":45},"action":"remove","lines":["request->get('title')"],"id":48},{"start":{"row":19,"column":24},"end":{"row":19,"column":25},"action":"insert","lines":["t"]},{"start":{"row":19,"column":25},"end":{"row":19,"column":26},"action":"insert","lines":["i"]},{"start":{"row":19,"column":26},"end":{"row":19,"column":27},"action":"insert","lines":["t"]},{"start":{"row":19,"column":27},"end":{"row":19,"column":28},"action":"insert","lines":["l"]},{"start":{"row":19,"column":28},"end":{"row":19,"column":29},"action":"insert","lines":["e"]}],[{"start":{"row":20,"column":30},"end":{"row":20,"column":57},"action":"remove","lines":["request->get('description')"],"id":49},{"start":{"row":20,"column":30},"end":{"row":20,"column":31},"action":"insert","lines":["d"]},{"start":{"row":20,"column":31},"end":{"row":20,"column":32},"action":"insert","lines":["e"]}],[{"start":{"row":20,"column":29},"end":{"row":20,"column":32},"action":"remove","lines":["$de"],"id":50},{"start":{"row":20,"column":29},"end":{"row":20,"column":41},"action":"insert","lines":["$description"]}],[{"start":{"row":31,"column":11},"end":{"row":31,"column":12},"action":"insert","lines":["s"],"id":51},{"start":{"row":31,"column":12},"end":{"row":31,"column":13},"action":"insert","lines":["t"]},{"start":{"row":31,"column":13},"end":{"row":31,"column":14},"action":"insert","lines":["a"]},{"start":{"row":31,"column":14},"end":{"row":31,"column":15},"action":"insert","lines":["t"]},{"start":{"row":31,"column":15},"end":{"row":31,"column":16},"action":"insert","lines":["i"]},{"start":{"row":31,"column":16},"end":{"row":31,"column":17},"action":"insert","lines":["c"]}],[{"start":{"row":31,"column":17},"end":{"row":31,"column":18},"action":"insert","lines":[" "],"id":52}],[{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["$"],"id":53},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["t"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["a"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["s"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["k"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["S"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["e"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["r"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["v"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["i"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["c"]},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["e"]}],[{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":["-"],"id":54},{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"remove","lines":[">"]}],[{"start":{"row":17,"column":22},"end":{"row":17,"column":23},"action":"insert","lines":["s"],"id":55},{"start":{"row":17,"column":23},"end":{"row":17,"column":24},"action":"insert","lines":["e"]},{"start":{"row":17,"column":24},"end":{"row":17,"column":25},"action":"insert","lines":["l"]},{"start":{"row":17,"column":25},"end":{"row":17,"column":26},"action":"insert","lines":["f"]}],[{"start":{"row":17,"column":26},"end":{"row":17,"column":27},"action":"insert","lines":[":"],"id":56},{"start":{"row":17,"column":27},"end":{"row":17,"column":28},"action":"insert","lines":[":"]}],[{"start":{"row":31,"column":44},"end":{"row":32,"column":0},"action":"insert","lines":["",""],"id":57},{"start":{"row":32,"column":0},"end":{"row":32,"column":8},"action":"insert","lines":["        "]}],[{"start":{"row":32,"column":8},"end":{"row":33,"column":6},"action":"remove","lines":["","      "],"id":58}],[{"start":{"row":32,"column":8},"end":{"row":32,"column":9},"action":"insert","lines":["к"],"id":59},{"start":{"row":32,"column":9},"end":{"row":32,"column":10},"action":"insert","lines":["у"]},{"start":{"row":32,"column":10},"end":{"row":32,"column":11},"action":"insert","lines":["е"]},{"start":{"row":32,"column":11},"end":{"row":32,"column":12},"action":"insert","lines":["г"]},{"start":{"row":32,"column":12},"end":{"row":32,"column":13},"action":"insert","lines":["к"]},{"start":{"row":32,"column":13},"end":{"row":32,"column":14},"action":"insert","lines":["т"]}],[{"start":{"row":32,"column":13},"end":{"row":32,"column":14},"action":"remove","lines":["т"],"id":60},{"start":{"row":32,"column":12},"end":{"row":32,"column":13},"action":"remove","lines":["к"]},{"start":{"row":32,"column":11},"end":{"row":32,"column":12},"action":"remove","lines":["г"]},{"start":{"row":32,"column":10},"end":{"row":32,"column":11},"action":"remove","lines":["е"]},{"start":{"row":32,"column":9},"end":{"row":32,"column":10},"action":"remove","lines":["у"]},{"start":{"row":32,"column":8},"end":{"row":32,"column":9},"action":"remove","lines":["к"]}],[{"start":{"row":32,"column":8},"end":{"row":32,"column":9},"action":"insert","lines":["r"],"id":61},{"start":{"row":32,"column":9},"end":{"row":32,"column":10},"action":"insert","lines":["e"]},{"start":{"row":32,"column":10},"end":{"row":32,"column":11},"action":"insert","lines":["t"]},{"start":{"row":32,"column":11},"end":{"row":32,"column":12},"action":"insert","lines":["u"]},{"start":{"row":32,"column":12},"end":{"row":32,"column":13},"action":"insert","lines":["r"]},{"start":{"row":32,"column":13},"end":{"row":32,"column":14},"action":"insert","lines":["n"]}],[{"start":{"row":32,"column":14},"end":{"row":32,"column":15},"action":"insert","lines":[" "],"id":62}],[{"start":{"row":32,"column":15},"end":{"row":32,"column":16},"action":"insert","lines":["T"],"id":63},{"start":{"row":32,"column":16},"end":{"row":32,"column":17},"action":"insert","lines":["a"]},{"start":{"row":32,"column":17},"end":{"row":32,"column":18},"action":"insert","lines":["s"]},{"start":{"row":32,"column":18},"end":{"row":32,"column":19},"action":"insert","lines":["k"]}],[{"start":{"row":32,"column":19},"end":{"row":32,"column":20},"action":"insert","lines":[":"],"id":64},{"start":{"row":32,"column":20},"end":{"row":32,"column":21},"action":"insert","lines":[":"]}],[{"start":{"row":32,"column":21},"end":{"row":32,"column":22},"action":"insert","lines":["s"],"id":65},{"start":{"row":32,"column":22},"end":{"row":32,"column":23},"action":"insert","lines":["e"]},{"start":{"row":32,"column":23},"end":{"row":32,"column":24},"action":"insert","lines":["l"]},{"start":{"row":32,"column":24},"end":{"row":32,"column":25},"action":"insert","lines":["e"]},{"start":{"row":32,"column":25},"end":{"row":32,"column":26},"action":"insert","lines":["c"]},{"start":{"row":32,"column":26},"end":{"row":32,"column":27},"action":"insert","lines":["t"]}],[{"start":{"row":32,"column":27},"end":{"row":32,"column":29},"action":"insert","lines":["()"],"id":66}],[{"start":{"row":32,"column":28},"end":{"row":32,"column":30},"action":"insert","lines":["''"],"id":67}],[{"start":{"row":31,"column":42},"end":{"row":31,"column":43},"action":"insert","lines":[":"],"id":68}],[{"start":{"row":31,"column":43},"end":{"row":31,"column":44},"action":"insert","lines":[" "],"id":69},{"start":{"row":31,"column":44},"end":{"row":31,"column":45},"action":"insert","lines":["i"]},{"start":{"row":31,"column":45},"end":{"row":31,"column":46},"action":"insert","lines":["n"]},{"start":{"row":31,"column":46},"end":{"row":31,"column":47},"action":"insert","lines":["t"]}],[{"start":{"row":31,"column":48},"end":{"row":32,"column":0},"action":"insert","lines":["",""],"id":70},{"start":{"row":32,"column":0},"end":{"row":32,"column":4},"action":"insert","lines":["    "]}],[{"start":{"row":33,"column":31},"end":{"row":33,"column":32},"action":"insert","lines":[";"],"id":71}],[{"start":{"row":33,"column":29},"end":{"row":33,"column":30},"action":"insert","lines":["е"],"id":72},{"start":{"row":33,"column":30},"end":{"row":33,"column":31},"action":"insert","lines":["ф"]},{"start":{"row":33,"column":31},"end":{"row":33,"column":32},"action":"insert","lines":["ы"]},{"start":{"row":33,"column":32},"end":{"row":33,"column":33},"action":"insert","lines":["л"]}],[{"start":{"row":33,"column":32},"end":{"row":33,"column":33},"action":"remove","lines":["л"],"id":73},{"start":{"row":33,"column":31},"end":{"row":33,"column":32},"action":"remove","lines":["ы"]},{"start":{"row":33,"column":30},"end":{"row":33,"column":31},"action":"remove","lines":["ф"]},{"start":{"row":33,"column":29},"end":{"row":33,"column":30},"action":"remove","lines":["е"]}],[{"start":{"row":33,"column":29},"end":{"row":33,"column":30},"action":"insert","lines":["t"],"id":74},{"start":{"row":33,"column":30},"end":{"row":33,"column":31},"action":"insert","lines":["a"]},{"start":{"row":33,"column":31},"end":{"row":33,"column":32},"action":"insert","lines":["s"]},{"start":{"row":33,"column":32},"end":{"row":33,"column":33},"action":"insert","lines":["k"]}],[{"start":{"row":33,"column":35},"end":{"row":33,"column":36},"action":"insert","lines":["-"],"id":75},{"start":{"row":33,"column":36},"end":{"row":33,"column":37},"action":"insert","lines":[">"]},{"start":{"row":33,"column":37},"end":{"row":33,"column":38},"action":"insert","lines":["m"]},{"start":{"row":33,"column":38},"end":{"row":33,"column":39},"action":"insert","lines":["a"]}],[{"start":{"row":33,"column":39},"end":{"row":33,"column":40},"action":"insert","lines":["x"],"id":76}],[{"start":{"row":33,"column":40},"end":{"row":33,"column":42},"action":"insert","lines":["()"],"id":77}],[{"start":{"row":33,"column":13},"end":{"row":33,"column":14},"action":"remove","lines":["n"],"id":78},{"start":{"row":33,"column":12},"end":{"row":33,"column":13},"action":"remove","lines":["r"]},{"start":{"row":33,"column":11},"end":{"row":33,"column":12},"action":"remove","lines":["u"]},{"start":{"row":33,"column":10},"end":{"row":33,"column":11},"action":"remove","lines":["t"]},{"start":{"row":33,"column":9},"end":{"row":33,"column":10},"action":"remove","lines":["e"]},{"start":{"row":33,"column":8},"end":{"row":33,"column":9},"action":"remove","lines":["r"]}],[{"start":{"row":33,"column":8},"end":{"row":33,"column":9},"action":"insert","lines":["$"],"id":79},{"start":{"row":33,"column":9},"end":{"row":33,"column":10},"action":"insert","lines":["m"]},{"start":{"row":33,"column":10},"end":{"row":33,"column":11},"action":"insert","lines":["a"]}],[{"start":{"row":33,"column":11},"end":{"row":33,"column":12},"action":"insert","lines":["x"],"id":80}],[{"start":{"row":33,"column":12},"end":{"row":33,"column":13},"action":"insert","lines":["T"],"id":81},{"start":{"row":33,"column":13},"end":{"row":33,"column":14},"action":"insert","lines":["a"]}],[{"start":{"row":33,"column":13},"end":{"row":33,"column":14},"action":"remove","lines":["a"],"id":82},{"start":{"row":33,"column":12},"end":{"row":33,"column":13},"action":"remove","lines":["T"]}],[{"start":{"row":33,"column":12},"end":{"row":33,"column":13},"action":"insert","lines":["T"],"id":83},{"start":{"row":33,"column":13},"end":{"row":33,"column":14},"action":"insert","lines":["a"]},{"start":{"row":33,"column":14},"end":{"row":33,"column":15},"action":"insert","lines":["s"]}],[{"start":{"row":33,"column":14},"end":{"row":33,"column":15},"action":"remove","lines":["s"],"id":84},{"start":{"row":33,"column":13},"end":{"row":33,"column":14},"action":"remove","lines":["a"]},{"start":{"row":33,"column":12},"end":{"row":33,"column":13},"action":"remove","lines":["T"]},{"start":{"row":33,"column":11},"end":{"row":33,"column":12},"action":"remove","lines":["x"]},{"start":{"row":33,"column":10},"end":{"row":33,"column":11},"action":"remove","lines":["a"]},{"start":{"row":33,"column":9},"end":{"row":33,"column":10},"action":"remove","lines":["m"]}],[{"start":{"row":33,"column":9},"end":{"row":33,"column":10},"action":"insert","lines":["l"],"id":85},{"start":{"row":33,"column":10},"end":{"row":33,"column":11},"action":"insert","lines":["a"]},{"start":{"row":33,"column":11},"end":{"row":33,"column":12},"action":"insert","lines":["s"]},{"start":{"row":33,"column":12},"end":{"row":33,"column":13},"action":"insert","lines":["t"]},{"start":{"row":33,"column":13},"end":{"row":33,"column":14},"action":"insert","lines":["T"]}],[{"start":{"row":33,"column":14},"end":{"row":33,"column":15},"action":"insert","lines":["a"],"id":86},{"start":{"row":33,"column":15},"end":{"row":33,"column":16},"action":"insert","lines":["s"]},{"start":{"row":33,"column":16},"end":{"row":33,"column":17},"action":"insert","lines":["k"]}],[{"start":{"row":33,"column":17},"end":{"row":33,"column":18},"action":"insert","lines":[" "],"id":87},{"start":{"row":33,"column":18},"end":{"row":33,"column":19},"action":"insert","lines":["="]}],[{"start":{"row":33,"column":48},"end":{"row":34,"column":0},"action":"insert","lines":["",""],"id":88},{"start":{"row":34,"column":0},"end":{"row":34,"column":8},"action":"insert","lines":["        "]},{"start":{"row":34,"column":8},"end":{"row":35,"column":0},"action":"insert","lines":["",""]},{"start":{"row":35,"column":0},"end":{"row":35,"column":8},"action":"insert","lines":["        "]}],[{"start":{"row":35,"column":8},"end":{"row":35,"column":9},"action":"insert","lines":["r"],"id":89},{"start":{"row":35,"column":9},"end":{"row":35,"column":10},"action":"insert","lines":["e"]},{"start":{"row":35,"column":10},"end":{"row":35,"column":11},"action":"insert","lines":["t"]},{"start":{"row":35,"column":11},"end":{"row":35,"column":12},"action":"insert","lines":["u"]},{"start":{"row":35,"column":12},"end":{"row":35,"column":13},"action":"insert","lines":["r"]},{"start":{"row":35,"column":13},"end":{"row":35,"column":14},"action":"insert","lines":["n"]}],[{"start":{"row":35,"column":14},"end":{"row":35,"column":15},"action":"insert","lines":[" "],"id":90}],[{"start":{"row":35,"column":15},"end":{"row":35,"column":16},"action":"insert","lines":["$"],"id":91},{"start":{"row":35,"column":16},"end":{"row":35,"column":17},"action":"insert","lines":["t"]},{"start":{"row":35,"column":17},"end":{"row":35,"column":18},"action":"insert","lines":["e"]},{"start":{"row":35,"column":18},"end":{"row":35,"column":19},"action":"insert","lines":["a"]}],[{"start":{"row":35,"column":19},"end":{"row":35,"column":20},"action":"insert","lines":["k"],"id":92}],[{"start":{"row":35,"column":19},"end":{"row":35,"column":20},"action":"remove","lines":["k"],"id":93},{"start":{"row":35,"column":18},"end":{"row":35,"column":19},"action":"remove","lines":["a"]},{"start":{"row":35,"column":17},"end":{"row":35,"column":18},"action":"remove","lines":["e"]},{"start":{"row":35,"column":16},"end":{"row":35,"column":17},"action":"remove","lines":["t"]}],[{"start":{"row":35,"column":16},"end":{"row":35,"column":17},"action":"insert","lines":["l"],"id":94},{"start":{"row":35,"column":17},"end":{"row":35,"column":18},"action":"insert","lines":["a"]},{"start":{"row":35,"column":18},"end":{"row":35,"column":19},"action":"insert","lines":["s"]},{"start":{"row":35,"column":19},"end":{"row":35,"column":20},"action":"insert","lines":["t"]}],[{"start":{"row":35,"column":15},"end":{"row":35,"column":20},"action":"remove","lines":["$last"],"id":95},{"start":{"row":35,"column":15},"end":{"row":35,"column":24},"action":"insert","lines":["$lastTask"]}],[{"start":{"row":35,"column":24},"end":{"row":35,"column":25},"action":"insert","lines":[" "],"id":96},{"start":{"row":35,"column":25},"end":{"row":35,"column":26},"action":"insert","lines":[":"]}],[{"start":{"row":35,"column":25},"end":{"row":35,"column":26},"action":"remove","lines":[":"],"id":97}],[{"start":{"row":35,"column":25},"end":{"row":35,"column":26},"action":"insert","lines":["?"],"id":98}],[{"start":{"row":35,"column":26},"end":{"row":35,"column":27},"action":"insert","lines":[" "],"id":99}],[{"start":{"row":35,"column":27},"end":{"row":35,"column":28},"action":"insert","lines":["$"],"id":100},{"start":{"row":35,"column":28},"end":{"row":35,"column":29},"action":"insert","lines":["l"]},{"start":{"row":35,"column":29},"end":{"row":35,"column":30},"action":"insert","lines":["a"]}],[{"start":{"row":35,"column":27},"end":{"row":35,"column":30},"action":"remove","lines":["$la"],"id":101},{"start":{"row":35,"column":27},"end":{"row":35,"column":36},"action":"insert","lines":["$lastTask"]}],[{"start":{"row":35,"column":36},"end":{"row":35,"column":37},"action":"insert","lines":[" "],"id":102},{"start":{"row":35,"column":37},"end":{"row":35,"column":38},"action":"insert","lines":[":"]}],[{"start":{"row":35,"column":38},"end":{"row":35,"column":39},"action":"insert","lines":[" "],"id":103}],[{"start":{"row":35,"column":39},"end":{"row":35,"column":54},"action":"insert","lines":["92.61.68.240/28"],"id":104}],[{"start":{"row":35,"column":53},"end":{"row":35,"column":54},"action":"remove","lines":["8"],"id":105},{"start":{"row":35,"column":52},"end":{"row":35,"column":53},"action":"remove","lines":["2"]},{"start":{"row":35,"column":51},"end":{"row":35,"column":52},"action":"remove","lines":["/"]},{"start":{"row":35,"column":50},"end":{"row":35,"column":51},"action":"remove","lines":["0"]},{"start":{"row":35,"column":49},"end":{"row":35,"column":50},"action":"remove","lines":["4"]},{"start":{"row":35,"column":48},"end":{"row":35,"column":49},"action":"remove","lines":["2"]},{"start":{"row":35,"column":47},"end":{"row":35,"column":48},"action":"remove","lines":["."]},{"start":{"row":35,"column":46},"end":{"row":35,"column":47},"action":"remove","lines":["8"]},{"start":{"row":35,"column":45},"end":{"row":35,"column":46},"action":"remove","lines":["6"]},{"start":{"row":35,"column":44},"end":{"row":35,"column":45},"action":"remove","lines":["."]},{"start":{"row":35,"column":43},"end":{"row":35,"column":44},"action":"remove","lines":["1"]},{"start":{"row":35,"column":42},"end":{"row":35,"column":43},"action":"remove","lines":["6"]},{"start":{"row":35,"column":41},"end":{"row":35,"column":42},"action":"remove","lines":["."]},{"start":{"row":35,"column":40},"end":{"row":35,"column":41},"action":"remove","lines":["2"]}],[{"start":{"row":35,"column":39},"end":{"row":35,"column":40},"action":"remove","lines":["9"],"id":106}],[{"start":{"row":35,"column":39},"end":{"row":35,"column":40},"action":"insert","lines":["$"],"id":107}],[{"start":{"row":35,"column":39},"end":{"row":35,"column":40},"action":"remove","lines":["$"],"id":108},{"start":{"row":35,"column":39},"end":{"row":35,"column":48},"action":"insert","lines":["$lastTask"]}],[{"start":{"row":35,"column":48},"end":{"row":35,"column":49},"action":"insert","lines":[" "],"id":109},{"start":{"row":35,"column":49},"end":{"row":35,"column":50},"action":"insert","lines":["+"]}],[{"start":{"row":35,"column":50},"end":{"row":35,"column":51},"action":"insert","lines":[" "],"id":110},{"start":{"row":35,"column":51},"end":{"row":35,"column":52},"action":"insert","lines":["1"]},{"start":{"row":35,"column":52},"end":{"row":35,"column":53},"action":"insert","lines":[";"]}],[{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["$"],"id":111},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["t"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["a"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["e"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["k"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["S"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["e"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["r"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["v"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["i"]}],[{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["c"],"id":112},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["e"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":["-"]},{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"remove","lines":[">"]}],[{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"insert","lines":["s"],"id":113},{"start":{"row":18,"column":27},"end":{"row":18,"column":28},"action":"insert","lines":["e"]},{"start":{"row":18,"column":28},"end":{"row":18,"column":29},"action":"insert","lines":["l"]},{"start":{"row":18,"column":29},"end":{"row":18,"column":30},"action":"insert","lines":["g"]}],[{"start":{"row":18,"column":29},"end":{"row":18,"column":30},"action":"remove","lines":["g"],"id":114}],[{"start":{"row":18,"column":29},"end":{"row":18,"column":30},"action":"insert","lines":["f"],"id":115},{"start":{"row":18,"column":30},"end":{"row":18,"column":31},"action":"insert","lines":[":"]},{"start":{"row":18,"column":31},"end":{"row":18,"column":32},"action":"insert","lines":[":"]}],[{"start":{"row":14,"column":33},"end":{"row":14,"column":34},"action":"insert","lines":["N"],"id":116},{"start":{"row":14,"column":34},"end":{"row":14,"column":35},"action":"insert","lines":["e"]},{"start":{"row":14,"column":35},"end":{"row":14,"column":36},"action":"insert","lines":["w"]}],[{"start":{"row":14,"column":36},"end":{"row":14,"column":37},"action":"insert","lines":["T"],"id":117},{"start":{"row":14,"column":37},"end":{"row":14,"column":38},"action":"insert","lines":["a"]},{"start":{"row":14,"column":38},"end":{"row":14,"column":39},"action":"insert","lines":["s"]},{"start":{"row":14,"column":39},"end":{"row":14,"column":40},"action":"insert","lines":["k"]}],[{"start":{"row":22,"column":28},"end":{"row":22,"column":53},"action":"remove","lines":["$request->get('sequence')"],"id":118},{"start":{"row":22,"column":28},"end":{"row":22,"column":29},"action":"insert","lines":["1"]}],[{"start":{"row":14,"column":93},"end":{"row":14,"column":94},"action":"insert","lines":[","],"id":119}],[{"start":{"row":14,"column":94},"end":{"row":14,"column":95},"action":"insert","lines":[" "],"id":120}],[{"start":{"row":14,"column":95},"end":{"row":14,"column":96},"action":"insert","lines":["U"],"id":121},{"start":{"row":14,"column":96},"end":{"row":14,"column":97},"action":"insert","lines":["s"]},{"start":{"row":14,"column":97},"end":{"row":14,"column":98},"action":"insert","lines":["e"]},{"start":{"row":14,"column":98},"end":{"row":14,"column":99},"action":"insert","lines":["r"]}],[{"start":{"row":14,"column":99},"end":{"row":14,"column":100},"action":"insert","lines":[" "],"id":122},{"start":{"row":14,"column":100},"end":{"row":14,"column":101},"action":"insert","lines":["$"]}],[{"start":{"row":14,"column":101},"end":{"row":14,"column":102},"action":"insert","lines":["u"],"id":123},{"start":{"row":14,"column":102},"end":{"row":14,"column":103},"action":"insert","lines":["s"]},{"start":{"row":14,"column":103},"end":{"row":14,"column":104},"action":"insert","lines":["e"]},{"start":{"row":14,"column":104},"end":{"row":14,"column":105},"action":"insert","lines":["r"]}],[{"start":{"row":21,"column":24},"end":{"row":21,"column":47},"action":"remove","lines":["$request->get('status')"],"id":124},{"start":{"row":21,"column":24},"end":{"row":21,"column":25},"action":"insert","lines":["1"]}],[{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"insert","lines":["/"],"id":125},{"start":{"row":18,"column":27},"end":{"row":18,"column":28},"action":"insert","lines":["*"]}],[{"start":{"row":18,"column":54},"end":{"row":18,"column":55},"action":"insert","lines":["*"],"id":126},{"start":{"row":18,"column":55},"end":{"row":18,"column":56},"action":"insert","lines":["/"]}],[{"start":{"row":18,"column":26},"end":{"row":18,"column":27},"action":"insert","lines":["1"],"id":127}],[{"start":{"row":23,"column":26},"end":{"row":23,"column":50},"action":"remove","lines":["request->get('sequence')"],"id":128},{"start":{"row":23,"column":26},"end":{"row":23,"column":27},"action":"insert","lines":["u"]},{"start":{"row":23,"column":27},"end":{"row":23,"column":28},"action":"insert","lines":["s"]},{"start":{"row":23,"column":28},"end":{"row":23,"column":29},"action":"insert","lines":["e"]},{"start":{"row":23,"column":29},"end":{"row":23,"column":30},"action":"insert","lines":["r"]},{"start":{"row":23,"column":30},"end":{"row":23,"column":31},"action":"insert","lines":["-"]}],[{"start":{"row":23,"column":31},"end":{"row":23,"column":32},"action":"insert","lines":[">"],"id":129},{"start":{"row":23,"column":32},"end":{"row":23,"column":33},"action":"insert","lines":["i"]},{"start":{"row":23,"column":33},"end":{"row":23,"column":34},"action":"insert","lines":["d"]}],[{"start":{"row":5,"column":20},"end":{"row":6,"column":0},"action":"insert","lines":["",""],"id":130},{"start":{"row":6,"column":0},"end":{"row":6,"column":1},"action":"insert","lines":["г"]},{"start":{"row":6,"column":1},"end":{"row":6,"column":2},"action":"insert","lines":["ы"]},{"start":{"row":6,"column":2},"end":{"row":6,"column":3},"action":"insert","lines":["у"]}],[{"start":{"row":6,"column":3},"end":{"row":6,"column":4},"action":"insert","lines":[" "],"id":131}],[{"start":{"row":6,"column":3},"end":{"row":6,"column":4},"action":"remove","lines":[" "],"id":132},{"start":{"row":6,"column":2},"end":{"row":6,"column":3},"action":"remove","lines":["у"]},{"start":{"row":6,"column":1},"end":{"row":6,"column":2},"action":"remove","lines":["ы"]},{"start":{"row":6,"column":0},"end":{"row":6,"column":1},"action":"remove","lines":["г"]}],[{"start":{"row":6,"column":0},"end":{"row":6,"column":1},"action":"insert","lines":["u"],"id":133},{"start":{"row":6,"column":1},"end":{"row":6,"column":2},"action":"insert","lines":["s"]},{"start":{"row":6,"column":2},"end":{"row":6,"column":3},"action":"insert","lines":["e"]}],[{"start":{"row":6,"column":3},"end":{"row":6,"column":4},"action":"insert","lines":[" "],"id":134},{"start":{"row":6,"column":4},"end":{"row":6,"column":5},"action":"insert","lines":["A"]},{"start":{"row":6,"column":5},"end":{"row":6,"column":6},"action":"insert","lines":["p"]},{"start":{"row":6,"column":6},"end":{"row":6,"column":7},"action":"insert","lines":["p"]}],[{"start":{"row":6,"column":7},"end":{"row":6,"column":8},"action":"insert","lines":["\\"],"id":135},{"start":{"row":6,"column":8},"end":{"row":6,"column":9},"action":"insert","lines":["U"]},{"start":{"row":6,"column":9},"end":{"row":6,"column":10},"action":"insert","lines":["s"]},{"start":{"row":6,"column":10},"end":{"row":6,"column":11},"action":"insert","lines":["e"]}],[{"start":{"row":6,"column":11},"end":{"row":6,"column":12},"action":"insert","lines":["C"],"id":136},{"start":{"row":6,"column":12},"end":{"row":6,"column":13},"action":"insert","lines":["a"]},{"start":{"row":6,"column":13},"end":{"row":6,"column":14},"action":"insert","lines":["s"]},{"start":{"row":6,"column":14},"end":{"row":6,"column":15},"action":"insert","lines":["e"]},{"start":{"row":6,"column":15},"end":{"row":6,"column":16},"action":"insert","lines":["\\"]}],[{"start":{"row":6,"column":16},"end":{"row":6,"column":17},"action":"insert","lines":["U"],"id":137},{"start":{"row":6,"column":17},"end":{"row":6,"column":18},"action":"insert","lines":["s"]},{"start":{"row":6,"column":18},"end":{"row":6,"column":19},"action":"insert","lines":["e"]},{"start":{"row":6,"column":19},"end":{"row":6,"column":20},"action":"insert","lines":["r"]},{"start":{"row":6,"column":20},"end":{"row":6,"column":21},"action":"insert","lines":["S"]},{"start":{"row":6,"column":21},"end":{"row":6,"column":22},"action":"insert","lines":["e"]},{"start":{"row":6,"column":22},"end":{"row":6,"column":23},"action":"insert","lines":["r"]}],[{"start":{"row":6,"column":23},"end":{"row":6,"column":24},"action":"insert","lines":["v"],"id":138},{"start":{"row":6,"column":24},"end":{"row":6,"column":25},"action":"insert","lines":["i"]},{"start":{"row":6,"column":25},"end":{"row":6,"column":26},"action":"insert","lines":["c"]},{"start":{"row":6,"column":26},"end":{"row":6,"column":27},"action":"insert","lines":["e"]}],[{"start":{"row":6,"column":27},"end":{"row":6,"column":28},"action":"insert","lines":[";"],"id":139}]]},"ace":{"folds":[],"scrolltop":0,"scrollleft":0,"selection":{"start":{"row":6,"column":28},"end":{"row":6,"column":28},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":0},"timestamp":1562149241525}