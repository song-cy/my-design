<?php

return [
    'alipay' => [
        'app_id'         => '2016101800713765',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqpYxZGufw5qwKXrfIaOZzdpqlESWJn0A6oJjKNJO6XcV5h28qmEJT2IftI88cG2ANKL5mTCYr3p5TBvjdj1vKhSsZyf+haJvDY+apxaT+b44Rdtk5olMoMwa3Rbr1J+T2NFxME8etuLCoVsEywGPQnXhod+7sZZjHqdvtEbPGUGcoveYdLz4KQWh2r1yvBgYCGKQVYrrHaOKGA3HdryM3yvnZYnNA4dUmIRsHFt+NPhkO0QuuyUqMWRSATyjC6hhvBdQ4Ev1Wq1MGzmffRmTRgbIb+ycBt31+Ccb2Otu44N8BH2puxPOg/wFvXCsFbh3+RsjpikZOI5pOs31uWZEBwIDAQAB',
        'private_key'    => 'MIIEpAIBAAKCAQEAsBrKqCSQ3TEzNtOP8X0CB1sAXL2xTCoIuo8mR5OTQUsuDYNZozk4FCsr9MkJT8/W5u3fCWtgw0taxF4Ac3zOypkrEjo7Nn0StIB6i98PwRQMf2zYnVSZiD4Z1ECIB07GNC74bUvQa4r0Xn33UGhALQQETeD1Nk/8UIH6jaTgfpabBnLfZPYfYT3JRwAMlmD91oi27YAmq6eNoNqOfa0zcpNqKV7JkxwQ+ORIybMdjK3XE+Ov/cBkJgwwCtp5KIuBrtzpjBKa6U8D1UwYQ5mzmZbFj3eSeMFhCFg2P/K1SS2T9aaXGBPcNttKuUVIcoRuldGrTIxYeu9jM9mAex4/sQIDAQABAoIBAFOmRkEcLVuD9UnDsIoK0R3hUgi7ai49gSvst0gkhzvqlBODIt7vXP3ni9eYbr3kgbXro5f701iwwIqqBBjXXfrKSrU7x8iYHHx00sEB0Z/rAAy/DR2eg9c9eFNj27ls56T98q8n5fZPaMhBmWouOt2nHMxJYPDNiZJMS1Fg0eoOn2yeGK20pXRdxI9w2GJtR+T7DxMuXXwC4T/kyWFRXoUyMj4gCleRff8Z/GqDpsoesFNRTCRuvHPPzGTSV/MXFxQ62tXcr1MwuuXGjyoUhpcyExLN3+mz/jgyYUYjYXdIsBHHf+6INAM1+LClJFe3QTNsRUUjdpRGroDI8+KGhIECgYEA+dsZucJr5ooRQVs+32bE4AvKgT5esXr2Ts1+y0SWuqEqmCqOZutFzUuUshm8ld0/yMuYb+JvgADcYmX3gxZg3gq7AIEDhPhGnUb54XUmWnbY4R4y4V77Ktb21z6OgYANtfnVqLtC2OoKUiNqPfrLCobvE0XvKP+5nercs0qWjSkCgYEAtG9pFxB6cI4fQB5/vBW5iaZ4HIjQPHoxFCv5teqYhVzPRjVVgTvttIQcZIDCVRy1iA6d6xO+OYBiV/4SzLYFYpC5sT+gw84ygS4C1RawZP8UMDlGP5X75VZMnoDvCTcF6zhDUs4adh9bQnHJ1Dv9zbFSVIPemR+fNPttXEBQ50kCgYEA3+qAYp6fror7rdPFQ9bJB3TptewcVvg4tySoxigg4P7AuoAhAJFHDBX5G6e+/5u5pwz2/wYyIMgkubZiHEO46iU5s9jrO4Z5WkgadvhTN+2crhsvRBSoCZt+uiXg9qO5JeXRmhbAcL9GIpiEhSk2P6fvqBkbnWWYhrLNuYxV4gkCgYBHWULKDwcLmPd8iFLkUgbjCoO2bNdDAuKDYnxE1jZnjfKchZyBFOyDFDaR+2Rc+ZRC79y8RztSS5UXzG0sq9FnT2lOUKXGp7PK5yHRlz47Qa5+/zbrD+jioAR+LOfY0fwyLjZY+Qz3pqCUnqA7n1lWTAcNSnPsQtfWwJFXCEu1mQKBgQCHKnPcxVIBBn72b2kfTlqcGF4l7dPj+20loacQjKOFs2SQZJrrrNJMR0xBJnqc0Jsn/P7CnKWnPAdhg9gj169z86zhSo1bT9F5RBeAHviLJYcX2UL7MZrwiiqx5kmaEimHT4Nixn8nX/Gbuzh40hd09kwWkViv+Zz4puEV/8UVgg==',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
