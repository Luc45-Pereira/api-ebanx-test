### API Processo seletivo Ebanx


```mermaid
%%{init: {'theme':'neutral'}}%%

flowchart TD
    API --> Routes

    subgraph Routes
        direction TB
        /reset[Reset Route] --> resetAction[POST /reset] --> resetResponse[200 OK]
        /balance[Balance Route] --> balanceAction[GET /balance?account_id] --> balanceCondition{"Account Exists?"}
        /event[Event Route] --> eventAction[POST /event] --> eventType{"Event Type"}
    end

    balanceCondition -- "Yes" --> balanceSuccess[200 20]
    balanceCondition -- "No" --> balanceFail[404 0]

    subgraph EventHandling
        direction TB
        eventType -- "Deposit" --> depositCondition{"Account Exists?"}
        depositCondition -- "Yes" --> depositSuccess["destination: id:100 balance:10"]
        depositCondition -- "No" --> depositFail["destination: id:100 balance:20"]

        eventType -- "Transfer" --> transferCondition{"Account Exists?"}
        transferCondition -- "Yes" --> transferSuccess["origin: id:100, balance:0; destination: id:300, balance:15"]
        transferCondition -- "No" --> transferFail[404 0]

        eventType -- "Withdraw" --> withdrawCondition{"Account Exists?"}
        withdrawCondition -- "Yes" --> withdrawSuccess["origin: id:100, balance:15"]
        withdrawCondition -- "No" --> withdrawFail[404 0]
    end
